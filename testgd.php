<?php
// Cek apakah GD sudah terinstall
if (extension_loaded('gd')) {
    echo 'GD installed!';
} else {
    echo 'GD not installed :(';
}
