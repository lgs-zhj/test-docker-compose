<?php 
$link=mysql_connect("119.8.45.170","wordpress","wordpress"); 
if(!$link) echo "FAILD!连接错误，用户名密码不对"; 
else echo "OK!可以连接"; 
?> 
