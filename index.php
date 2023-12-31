<?php

$conn = new mysqli('localhost', "root", "", "ratingSystem") ;
if(isset($_POST['save'])){
    $uID = $conn->real_escape_string( $_POST['uID']);
    $ratedIndex = $conn->real_escape_string($_POST['ratedIndex']);
    // $comment = $conn->real_escape_string($_POST['comment']);
    $ratedIndex ++;
    if(!$uID){
        $conn->query("INSERT INTO stars (ratedIndex) VALUES('$ratedIndex')");
        $sql = $conn->query("SELECT id FROM stars ORDER BY id DESC LIMIT 1");
        $uData = $sql->fetch_assoc();
        $uID = $uData['id'];
        echo "succesefully";
    }else{
        $conn->query("UPDATE stars SET retedIndex = `$ratedIndex` where id = '$uID'");
    }
    exit(json_encode(array('id'=> $uID)));
}

$sql = $conn->query('SELECT id From stars');
$numR = $sql->num_rows;

$sql = $conn->query('SELECT SUM (ratedIndex) as total from stars');
$rDATA = $sql->fetch_array();
$total = $rDATA['total'];
$avg = $total / $numR;
var_dump($_POST);

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Bootstrap demo</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
  </head>
  <body>
    <style>
      * {
        padding: 0px;
        margin: 0px;
        box-sizing: border-box;
      }
      .star-rate:hover {
        color: yellow;
      }
      .star-rate {
        color: white;
      }
    </style>
    <div class="bg-dark-subtle text-emphasis-dark">
      <div class="center container p-2">
        <form action="index.php" method="post" class="m-2">
          <div class="d-flex justify-content-center">
            <i class="fa fa-star star-rate" data-index="0"></i>
            <i class="fa fa-star star-rate" data-index="1"></i>
            <i class="fa fa-star star-rate" data-index="2"></i>
            <i class="fa fa-star star-rate" data-index="3"></i>
            <i class="fa fa-star star-rate" data-index="4"></i>
          </div>
          <textarea name="" id="" placeholder="your feedback" name="comment"></textarea>
          <button type="submit">send</button>
        </form>
      </div>
    </div>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://code.jquery.com/jquery-3.7.1.js"
      integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
      crossorigin="anonymous"
    ></script>
    <script>
      let ratedIndex = -1, uID = 0;
      $(document).ready(function () {
        resetStarColors();
        if (localStorage.getItem("ratedIndex") != null){
            uID = localStorage.getItem("uID")
            setStars(localStorage.getItem("ratedIndex"))
            console.log("bu rating bor");
        }
      
        $(".fa-star").on("click", function () {
          ratedIndex = parseInt($(this).data("index"));
          localStorage.setItem("ratedIndex", ratedIndex);
          saveToTheDB();
        });

        $(".fa-star").mouseover(function () {
          resetStarColors();
          let currentIndex = parseInt($(this).data("index"));
          setStars(currentIndex);
        });
        $(".fa-star").mouseleave(function () {
          resetStarColors();
          if (ratedIndex != -1) setStars(ratedIndex);
        });
      });
      function saveToTheDB(){
        $.ajax({
            url: 'index.php',
            method: "POST",
            dataType: 'json',
            data: {
                save : 1,
                uID:uID,
                ratedIndex: ratedIndex
            }, success :function(r){
                uID = r.id;
                localStorage.setItem('uID', uID)
            }
        })
      }
      function setStars(max) {
        for (var i = 0; i <= max; i++)
          $(".fa-star:eq(" + i + ")").css("color", "yellow");
      }
      function resetStarColors() {
        $(".fa-star").css("color", "white");
      }
    </script>
  </body>
</html>
