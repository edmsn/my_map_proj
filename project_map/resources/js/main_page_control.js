    var canvas = document.getElementById("mainCanvas");
    var ctx = canvas.getContext("2d");
    var point_radius = 15;
    var point_array =[];
    $(document).ready(function () {
        getSavedMaps();
        $('#generate').on('submit', function(e){
            onCreate(e);
        });
        $('#save_map').on('submit', function(e){
            onSave(e);
        });
        $('#load_map').on('submit', function(e){
            id = document.getElementById("map_selector").value;
            document.getElementById("start_point").value = "";
            document.getElementById("end_point").value = "";
            onLoad(id,e);
        });
        $('#calculate_path').on('submit', function(e){
            id = document.getElementById("map_id").value;
            onLoad(id,e);
            onCalculate(e);
        });
        $('#mainCanvas').on('click', function(e){
            handleMouseDown(e);
        });

    });
    /*
     * function for setting Point start and point end. 
     * sets only if one of point fields is empty 
     */
    function handleMouseDown(e){
        if(document.getElementById("start_point").value != "" && document.getElementById("end_point").value != "")
        {
            return;
        }
        var canvasOffset = $("#mainCanvas").offset();
        var offsetX = canvasOffset.left;
        var offsetY = canvasOffset.top;
        radius = 15;
        mouseX = parseInt(e.clientX - offsetX);
        mouseY = parseInt(e.clientY - offsetY);
        for (data_val in point_array){
             if (Math.abs(mouseX-point_array[data_val].x) < radius && Math.abs(mouseY-point_array[data_val].y) < radius  ){
                if(document.getElementById("start_point").value == ""){
                    document.getElementById("start_point").value = data_val;
                } else {
                    document.getElementById("end_point").value = data_val;
                }
                return;
            }
        }
    }
    /*
     * saves map with selected name to database
     */
    function onSave(e){
        e.preventDefault();
        var id = document.getElementById("map_id").value;
        var name = document.getElementById("map_name").value;
        if (id != 0){
            $.ajax({
                url : "server/ex_serv.php?action=save_map",
                type: "POST",
                data:  "&id=" + id + "&name=" + name,
                success: function (data) {
                    alert("Map successfully saved !");
                },
                error: function (jXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
        } else {
            alert("Map is not created");
        }

    }
    /*
     * Fully generates map, inserts it to database without name
     */
    function onCreate(e){
        e.preventDefault();
        var width = document.getElementById("map_width").value;
        var height = document.getElementById("map_height").value;
        $.ajax({
            url : "server/ex_serv.php?action=new_map",
            type: "GET",
            data:  "&width=" + width + "&height=" + height,
            dataType:"json",
            success: function (data) {
                setNewMap(data);
                document.getElementById("start_point").value = "";
                document.getElementById("end_point").value = "";
            },
            error: function (jXHR, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    }
    /*
     * creates points and link in canvas
     */
    function setNewMap(data){
        document.getElementById("map_id").value = data.id;
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        point_array = data.points;
         for (data_val in data.points){
           drawCircle(data.points[data_val]);
        }
        for (data_val in data.links){ //data.links[data_val]
            var start_x = data.points[data.links[data_val].start_point].x;
            var start_y = data.points[data.links[data_val].start_point].y;
            var end_x = data.points[data.links[data_val].end_point].x;
            var end_y = data.points[data.links[data_val].end_point].y;
            drawLine(start_x,start_y,end_x,end_y,data.links[data_val]);
        }
    }
    /*
     * draw Circle needs x/y coordinates and radius
     */
    function drawCircle(e){
        ctx.beginPath();
        ctx.fillStyle = "#000000";
        ctx.arc(e.x,e.y,point_radius,0,2 * Math.PI);
        ctx.fill();
    }
    /*
     * draw Line based on type
     */
    function drawLine(start_x,start_y,end_x,end_y,e){

        if(e.type != "blocked"){
            ctx.beginPath();
            ctx.moveTo(start_x,start_y);
            ctx.lineTo(end_x,end_y);
            ctx.stroke();
            if (start_x ==end_x){
                start_y += point_radius;
                end_y -=point_radius;
            } else 
            if (start_y ==end_y){
                start_x += point_radius;
                end_x -=point_radius;
            } 
            if(e.type == "to_end"){
                drawArrow(start_x,start_y,end_x,end_y);
            }else  if (e.type == "to_start"){
                drawArrow(end_x,end_y,start_x,start_y);
            }
            ctx.font = "30px Arial";
            ctx.beginPath();
            if(start_x == end_x){
                ctx.fillText(e.value,(start_x+end_x)/2,(start_y+end_y)/2 +10) ;
            }else{
               ctx.fillText(e.value,(start_x+end_x)/2 - 10,(start_y+end_y)/2) ; 
            }
        }
    }
    /*
     * draw arrow for line
     */
    function drawArrow(start_x,start_y,end_x,end_y){
        var headlen = 10; 
        var angle = Math.atan2(end_y-start_y,end_x-start_x);
        ctx.beginPath();
        ctx.lineWidth = 3;
        ctx.moveTo(end_x, end_y);
        ctx.lineTo(end_x-headlen*Math.cos(angle-Math.PI/6),end_y-headlen*Math.sin(angle-Math.PI/6));
        ctx.moveTo(end_x, end_y);
        ctx.lineTo(end_x-headlen*Math.cos(angle+Math.PI/6),end_y-headlen*Math.sin(angle+Math.PI/6));
        ctx.stroke();
        ctx.lineWidth = 1;
    }
    /*
     * gets existing map from db and draw it
     */
    function onLoad(id,e){
       e.preventDefault();
       $.ajax({
            url : "server/ex_serv.php?action=get_map",
            type: "GET",
            data:  "&id="+id,
            dataType:"json",
            success: function (data) {
                setNewMap(data);
            },
            error: function (jXHR, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    }  
    /*
     * calculate longest pass and shorted pass
     * display it in canvas and with alert messages shows information about them
     */
    function onCalculate(e){
        id = document.getElementById("map_id").value;
        start_point = document.getElementById("start_point").value;
        end_point = document.getElementById("end_point").value;
        if (id != 0){
            $.ajax({
                 url : "server/ex_serv.php?action=get_calculations",
                 type: "GET",
                 data:  "&id="+id +"&start_point=" + start_point +"&end_point=" + end_point,
                 dataType:"json",
                 success: function (data) {
                    if(data.fastest.length != 0){
                        var fast_array = data.fastest.point.split(":");
                        var long_array = data.longest.point.split(":");
                        drawWay(fast_array,"#00FF00",0);
                        drawWay(long_array,"#FF0000",2)
                        ctx.strokeStyle="#000000";
                        alert("Fastest path length:" + fast_array.length + ", it takes " + data.fastest.value + " minutes. Marked green!" );
                        alert("Fastest path length:" + long_array.length + ", it takes " + data.longest.value + " minutes. Marked red!" );
                    }else{
                        alert("Path do not exist.");
                    }
                        
                 },
                 error: function (jXHR, textStatus, errorThrown) {
                     alert(errorThrown);
                 }
             });
        }
    }  
    /*
     * draw way.
     * require path - array with points,color - for line color, offset - so lines would not cover each other
     */
    function drawWay(path_array,color,offset){
        ctx.beginPath();
        ctx.strokeStyle=color;
        ctx.moveTo(point_array[start_point].x,point_array[start_point].y);
        for (data_val in path_array){
            ctx.lineTo(point_array[path_array[data_val]].x - offset,point_array[path_array[data_val]].y - offset);

        }
        ctx.stroke();
    }
    /*
     * get list of maps saved by user and insert it to selector
     */
    function getSavedMaps(){
         $.ajax({
            url : "server/ex_serv.php?action=map_list",
            type: "GET",
            data:  "",
            dataType:"json",
            success: function (data) {
                var selector = document.getElementById("map_selector");
                for (data_val in data){
                   var option = document.createElement("option");
                   option.value = data[data_val].id;
                   option.text = data[data_val].name;
                   selector.add(option);
                }
            },
            error: function (jXHR, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
    }
   