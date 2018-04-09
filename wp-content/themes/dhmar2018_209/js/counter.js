var canvas = document.getElementsByTagName('canvas');

      for (var i = 0; i < canvas.length; i++) {
          progressBar(canvas[i].id);
      }

      // load the canvas
      function progressBar(canvasId) {
          var degreesCall;        
          
          var canvas = document.getElementById(canvasId);
          var ctx = canvas.getContext('2d');

          // declare some variables
          var cWidth = canvas.width;
          var cHeight = canvas.height;
          var progressColor = '#ffa31f';
          var circleColor = '#ffffff';
          var rawPerc = canvas.getAttribute('data-perc');
          var definition = canvas.getAttribute('data-text');
          var perc = parseInt(rawPerc);
          var degrees = 0;
          var endDegrees = (360*perc)/100;
          
          var lineWidth = 15; // The 'brush' size

          console.log(canvasId+' '+perc);

          function getDegrees() {
              if(degrees < endDegrees) {
                  degrees++;
              }
              else {
                  clearInterval(degreesCall);
              }

              drawProgressBar();
          }

          function drawProgressBar() {
              //clear the canvas after every instance
              ctx.clearRect(0,0,cWidth,cHeight);

              // let's draw the background circle
              ctx.beginPath();
              ctx.strokeStyle = circleColor;
              ctx.lineWidth = lineWidth -1;
              ctx.arc(cHeight/2, cWidth/2, cWidth/3, 0, Math.PI*2, false);
              ctx.stroke();
              var radians = 0; // We need to convert the degrees to radians

              radians = degrees * Math.PI/180;
              // let's draw the actual progressBar
              ctx.beginPath();
              ctx.strokeStyle = progressColor;
              ctx.lineWidth = lineWidth;
              ctx.arc(cHeight/2, cWidth/2, cWidth/3, 0 - 90*Math.PI/180, radians - 90*Math.PI/180, false);
              ctx.stroke();

              // let's get the text
              ctx.fillStyle = progressColor;
              ctx.font = '55px Arial';
              var num = degrees/100;
              var outputTextPerc = num.toFixed(1)
              
              var outputTextPercWidth = ctx.measureText(outputTextPerc).width;
              var outputTextDefinitionWidth = ctx.measureText(definition).width;
              ctx.fillText(outputTextPerc, cWidth/2 - outputTextPercWidth/2, cHeight/2 + 5 );
              ctx.fillText(definition, cWidth/2 - outputTextDefinitionWidth/2, cHeight/2 + 15);
          }

          degreesCall = setInterval(getDegrees, 10/(degrees - endDegrees));
      }
