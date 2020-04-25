<?php

if(isset($_SESSION['list_peminjaman'])) {
	foreach ($_SESSION['list_peminjaman'] as $list) {
		$explode = explode("-", $list['nama_barang']);
		$nama_barang = trim($explode[0]);
		$jenis = trim($explode[1]);

		$barang = $conn->query("SELECT * FROM barang WHERE nama_barang='$nama_barang' AND jenis = '$jenis'");
		$dt_barang = $barang->fetch_assoc();

		$pinjam = $conn->query("SELECT * FROM detail_pinjam WHERE id_barang = '$dt_barang[id_barang]'");

		$sisa = ($dt_barang['jumlah'] - $list['jumlah_pinjam']);

		if($pinjam->num_rows > 0) {
			$jml_pnj = ($dt_pinjam['jumlah'] + $list['jumlah']);

			$update = $conn->query("UPDATE detail_pinjam SET jumlah = '$jml_pnj' WHERE id_detail_pinjam = '$dt_pinjam[id_detail_pinjam]");

			$update_dt_brg = $conn->query("UPDATE barang SET jumlah = '$sisa' WHERE id_barang = '$dt_barang[id_barang]'");
		} else {
			$tgl_peminjaman = date('Y-m-d');
			$tgl_pengembalian = $_POST['tgl-pengembalian'];
			$peminjam = $_POST['peminjam'];
			$id_user = $_POST['id_user'];

			$peminjaman = $conn->query("INSERT INTO peminjaman VALUES ('', '$id_user', '$tgl_peminjaman', '$tgl_pengembalian')");

			$detail_pinjam = $conn->query("INSERT INTO detail_pinjam VALUES ('', '$list[id_barang]', '$list[jumlah_pinjam]', '$peminjam', (SELECT id_peminjaman FROM peminjaman ORDER BY id_peminjaman DESC LIMIT 1))");

			$update_dt_brg = $conn->query("UPDATE barang SET jumlah = '$sisa' WHERE id_barang = '$dt_barang[id_barang]'");
		}
	}	
	unset($_SESSION['list_peminjaman']);
}