let socket  = require("socket.io"),
    express = require("express")(),
    http    = require("http").createServer(express),
    io      = socket(http),
    port    = process.env.port || 3000;

io.listen(port, function() {
    console.log(port);
});

io.on("connection", function(sock) {

    // finish chat
    sock.on("finish", function(msg) {
        io.emit("finish", msg);
    });

    // send message
    sock.on("send", function(msg) {
        io.emit("send", msg);
    });

    // is typing
    sock.on("isTyping", function(msg) {
        io.emit("isTyping", msg);
    });
});