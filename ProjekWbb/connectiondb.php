<?php

$koneksi = mysqli_connect("localhost", "root", "", "proyek_timfdel");

if (mysqli_connect_error()) {
    echo "Koneksi database gagal : " . mysqli_connect_error();
}

?>