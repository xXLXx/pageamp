var app = require('express')();

var fs = require('fs');
var protocol = 'https';
var certPath = '~/certs/';
if (process.env.ENV == 'dev') {
	certPath = './';
}
var serverParams = {
	cert: fs.readFileSync(certPath + 'server.crt').toString(),
    key: fs.readFileSync(certPath + 'server.key').toString(),
    NPNProtocols: ['http/2.0', 'spdy', 'http/1.1', 'http/1.0']
};
if (process.env.ENV == 'dev') {
	protocol = 'http';
	serverParams = null;
}
var http = require(protocol).createServer(serverParams, app);
var io = require('socket.io')(http);

app.get('/', function(req, res){
  res.send('<h1>Oops! Lost? This is a socket server</h1>');
});

var port = process.env.PORT;
http.listen(port, function(){
  console.log('listening on *:' + port);
});

io.on('connection', function(socket){
  socket.on(process.env.SOCKET_TEST_STATUS_EVENT, function (data) {
  	io.emit(process.env.SOCKET_TEST_STATUS_EVENT + ':' + data.id, data.data);
  })
});
