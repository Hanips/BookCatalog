<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Flipbook</title>

    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Turn.js CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/turn.js/4.1.0/turn.min.js"></script>

    <style>
        #flipbook {
            width: 800px;
            height: 600px;
            margin: 20px auto;
            background-color: #f5f5f5;
        }
        .page {
            width: 400px;
            height: 600px;
            background: white;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>

    <!-- Flipbook Container -->
    <div id="flipbook">
        <div class="page">Page 1</div>
        <div class="page">Page 2</div>
        <div class="page">Page 3</div>
        <div class="page">Page 4</div>
    </div>

    <script>
        $(document).ready(function() {
            $("#flipbook").turn({
                width: 800,
                height: 600,
                autoCenter: true
            });
        });
    </script>

</body>
</html>
