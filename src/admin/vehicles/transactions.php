<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<style>
	.vehicle-thumbnail{
		width:3em;
		height:3em;
		object-fit:cover;
		object-position:center center;
	}
</style>
<div class="card card-outline rounded-0 card-navy">
	<div class="card-header">
		<h3 class="card-title">Daftar Data Transaksi</h3>
	</div>
	<div class="card-body">
        <div class="container-fluid">
			<table class="table table-hover table-striped table-bordered" id="list">
				<colgroup>
					<col width="5%">
					<col width="20%">
					<col width="20%">
					<col width="25%">
					<col width="20%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th>No</th>
						<th>Tanggal</th>
						<th>Nama Customer</th>
						<th>Kendaraan</th>
						<th>Harga</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
						$qry = $conn->query("SELECT t.*, concat(t.firstname,' ',COALESCE(concat(t.middlename,' '), ''), t.lastname) as customer, v.mv_number, v.plate_number, v.price, m.model, m.engine_type, m.transmission_type, b.name as `brand`, ct.name as `car_type` from transaction_list t inner join `vehicle_list` v on t.vehicle_id = v.id inner join model_list m on v.model_id = m.id inner join brand_list b on m.brand_id = b.id inner join car_type_list ct on m.car_type_id = ct.id where v.status = 1 and v.delete_flag = 0 order by abs(unix_timestamp(v.date_created)) asc ");
						while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
                            <td><?= date("Y-m-d H:i", strtotime($row['date_created'])) ?></td>
							<td class="">
								<div style="line-height:1em">
									<div><b><?= $row['customer'] ?></b></div>
									<div><small class="text-muted"><?= $row['email'] ?></small></div>
								</div>
							</td>
							<td class="">
								<div style="line-height:1em">
									<div><b><?= $row['brand'] ?> - <?= $row['model'] ?></b></div>
									<div><small class="text-muted">Nomor plat #: <?= $row['mv_number'] ?></small></div>
								</div>
							</td>
							<td class="text-right"><?= number_format($row['price'], 2) ?></td>
							<td align="center" class='text-center'>
								<a class="btn btn-flat btn-light bg-gradient-light btn-sm border" href="./?page=vehicles/view_transaction&id=<?= $row['id'] ?>"><i class="fa fa-eye"></i> Lihat Data</a>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('.table').dataTable({
			columnDefs: [
					{ orderable: false, targets: [5] }
			],
			order:[0,'asc']
		});
		$('.dataTable td,.dataTable th').addClass('py-1 px-2 align-middle')
		
	})
	function delete_vehicle($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_vehicle",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>