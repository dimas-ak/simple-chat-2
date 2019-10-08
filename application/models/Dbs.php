<?php
defined("BASEPATH") or exit('rika mau ngapa kang? :/');
class Dbs extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->library("menu");
    }

    function row_array($field, $value, $table)
    {
        return $this->db->where($field, $value, TRUE)->get($table)->row_array();
    }

    function result($table)
    {
        return $this->db->get($table)->result();
    }

    function result_key($field, $value, $table)
    {
        return $this->db->where($field, $value, TRUE)->get($table)->result();
    }

    function insert($data, $table)
    {
        return $this->db->insert($table, $data);
    }

    function update($field, $value, $data, $table)
    {
        return $this->db->where($field, $value, TRUE)->update($table, $data);
    }

    function isBusy()
    {
        // status 1     : Online
        // status 2     : Busy
        // return FALSE : Not Busy
        // TRUE         : Busy
        return ($this->db->where('status', 1, TRUE)->where("level", 2, TRUE)->get('admin')->num_rows() > 0 ) ? FALSE : TRUE;
    }
    
    function isDone($id_user)
    {
        //get last chat
        $user = $this->db->select('*')->from("chat")->where('chat.id_user', $id_user)->join("user", "user.id = chat.id_user")->limit(1)->order_by("chat.id",'desc')->get()->row_array();
        // get last time chat and make it to strtotime
        // + 900 means that date by last chat will adding for 30 min
        $time = strtotime($user['date']) + $this->menu->min();
        $now  = strtotime(date("Y-m-d H:i:s"));
        // check if date time by last chat user is greater than 30 min
        return ($now > $time && $user['status'] == 1) ? TRUE : FALSE;
    }

    // get user chat where id_admin || left side admin
    function getUserChat($id_admin, $isSuperAdmin = FALSE)
    {
        $delete_by_admin    = ($isSuperAdmin) ? 2 : 1;
        $isChat             = ($isSuperAdmin) ? 1 : 6;
        // delete_by_admin = 0 (before Officer WebChat delete the chat)
        // delete_by_admin = 1 (after Officer WebChat delete the chat)
        // $isChat = check if user having chat with Officer WebChat
        // status 1 = for Super Admin where Status user is not having chat with Officer WebChat
        // status 6 = for Admin where status not in (1-5)
        //SELECT * FROM chat where id in (SELECT max(id) FROM chat GROUP BY id_user ) order by id desc
        return $this->db->select("*, user.id as id_user, user.date AS date_join, chat.date as last_chat, review.star as user_star, user.status as status_user, review.message as reason")
        ->from('chat')
        ->join("user", "user.id = chat.id_user AND user.delete_by_admin != $delete_by_admin")
        ->join('review', 'review.id_user = chat.id_user', 'left')
        ->where('chat.id IN (SELECT MAX(chat.id) FROM chat GROUP BY chat.id_user)') // get last record with Group By first
        ->where("user.status != $isChat")
        ->where('chat.id_admin', $id_admin, TRUE)->order_by('chat.id','desc')
        ->get()->result();
    }

    // get main chat
    function getChat($id_user)
    {   
        return $this->db->select("*, user.name as name_user, admin.name as name_admin")->from("chat")->where('chat.id_user', $id_user)->join('user', 'user.id = chat.id_user')->join('admin', 'admin.id = chat.id_admin')->get()->result();
    }

    function review_star($id_admin, $star)
    {
        $result = $this->db->where('id_admin', $id_admin, TRUE)->where('star', $star, TRUE)->get('review')->result();
        return count($result);
    }
}