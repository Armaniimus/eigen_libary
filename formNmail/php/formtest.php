<head>
    <style media="screen">
        .hidden {
            /* color: white;
            font-size: 1;
            font-weight: 100;
            border: 0px black solid;
            height: 3px;
            width: 80px; */
        }
    </style>
</head>

<form class="" action="" method="post">
    <input class="hidden" type="text" name="hi" id="hi" value="" minlength="7" required oninvalid="validateHi()" onkeyup="validateHi()" onkeydown="validateHi()"><br>
    <script type="text/javascript">
        function validateHi() {
            const field = document.getElementById('hi');

            field.setCustomValidity('');
            if (!field.checkValidity() ) {
                field.setCustomValidity('hi moet minimaal 7 characters lang zijn');
            }
        }
    </script>
    <input type="submit" name="submit" value="verstuur">
</form>

<?php
if ( isset( $_POST["hi"] ) ) {
    $hi = $_POST["hi"];
    echo "
        <script>
            alert('$hi');
        </script>
    ";
}
