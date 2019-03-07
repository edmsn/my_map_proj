<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Project MAP</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="public/css/map.css" rel="stylesheet">
    </head>
    <body>
        <div class='container '>
            <form id='generate' style='width: 50%; margin: auto ' >
                <div class='form-inline'>
                    <input type='number' class="form-control col-sm-3" placeholder='Width' id='map_width' required>
                    <input type='number' class="form-control col-sm-3" placeholder='Height' id='map_height' required>
                    <input type='submit' value='Generate new Map' name='generate_map' form='generate' class="btn btn-success col-sm-6">
                </div>
            </form>
            <form id='save_map' method='' action='' style='width: 50%; margin: auto '>
                <div class='form-inline '>
                    <input type='text' class="form-control col-sm-6" placeholder='name your map' id='map_name' required>
                    <input type='submit' value='Save your Map' name='save_map' form='save_map' class="btn btn-primary col-sm-6">
                </div>
            </form>
            <form id='load_map' method='' action='' style='width: 50%; margin: auto ' >
                <div class='form-inline '>
                    <select id='map_selector' class='form-control col-sm-6 ' required>
                        <option value=""></option>
                    </select>
                    <input type='submit' value='Load selected Map' name='load_map' form='load_map' class="btn btn-success col-sm-6">
                </div>
            </form>
            <div class='form-group' >
                <canvas id="mainCanvas" width="900" height="400" style="border:2px solid #d3d3d3;" ></canvas>
            </div>
            <form id='calculate_path' method='' action='' style='width: 50%; margin: auto ' >
                <div class='form-inline '>
                    <input type='text' class="form-control col-sm-3" placeholder='Start point' id='start_point' required>
                    <input type='text' class="form-control col-sm-3" placeholder='End point' id='end_point' required>
                    <input type='submit' value='Calculate' name='calculate_path' form='calculate_path' class="btn btn-success col-sm-6">
                </div>
           </form>
            
            <input type="hidden" id="map_id">
        </div>
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script src="resources/js/main_page_control.js"></script>  
    
    </body>
</html>
