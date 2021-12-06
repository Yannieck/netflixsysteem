<!DOCTYPE html>
<html lang="en">

<head>
<style>
body {
  color: white;
}

h1 {
  color: white;
}

table {
  border-collapse: collapse;
  border-spacing: 0;
  width: 550px;
  border: 1px solid #black;
}

.container {
  margin: 0 auto; 
  background: rgba(0, 0, 0, 0.5);
  padding: 50px;
  padding-top: 25px; 
}

.headertext {
  margin: 0 auto;
  text-align: center;
}

th, td {
  text-align: left;
  padding: 8px;
}

.select {
background-color: transparent !important;
}

tr:nth-child(even) {
  background-color: #292929;
}



</style>
    <?php include_once('../assets/components/head.php')?>
    <link rel="stylesheet" href="../assets/styles/style.css">
</head>

<body>
    <div class="background centervertical">
    <img src="../assets/img/lightlogo.svg" alt="Logo" class="logo">


  

<div class="container"> 
<h1 class="headertext"> Select membership </h1>

<table>
  <tr>
      <th></th>
      <th><h2>Junior</h2></th>
      <th><h2>Senior</h2></th>
  </tr> 
  <tr>
      <td><p>Montly price</P></td>
      <td><p>$9,99</p></td>
      <td><p>$14,99</p></td>
  </tr>
  <tr>
      <td><p>HD available</p></td>
      <td><p>&#10003;</p></td>
      <td><p>&#10003;</p></td>
  </tr>
  <tr>
      <td><p>4k available</p></td>
      <td><p>&#10060;</p></td>
      <td><p>&#10003;</p></td>
  </tr>
  <tr>
      <td><p>Watch on your laptop or TV</p></td>
      <td><p>&#10003;</p></td>
      <td><p>&#10003;</p></td>
  </tr>
  <tr>
      <td><p> Unlimited video's</p></td>
      <td><p>&#10003;</p></td>
      <td><p>&#10003;</p></td>
  </tr>
  <tr>
      <td><p>Cancel anytime</p></td>
      <td><p>&#10003;</p></td>
      <td><p>&#10003;</p></td> 
  </tr>
  <tr class="select">
    <th></th>
    <th><h2><p>Select</p></h2></th>
    <th><h2><p>Select</p></h2></th>
  </tr>
</table> 
</div>
</body>