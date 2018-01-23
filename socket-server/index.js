var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);

app.get('/', function(req, res){
  res.send('<h1>Oops! Lost? This is a socket server</h1>');
});

var port = process.env.PORT;
http.listen(port, function(){
  console.log('listening on *:' + port);
});

io.on('connection', function(socket){
  
});
