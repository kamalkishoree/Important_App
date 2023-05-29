<style>
.table td:last-child {
  text-align: center;
}
.table tr:last-child td, .table tr:last-child th {
  border: 0;
}
.table tr:first-child td, .table tr:first-child th {
  font-weight: 700;
}
.table th, .table td {
	padding: 10px 20px;
	border-right: 0;
	border-left: 0;
	border-top: 0;
}
#classTable {
	border: 0;
}
</style>
<table id="classTable" class="table table-bordered">
	<thead>
	</thead>
	<tbody>
		<tr>
			<td>#</td>
			<td>Product Name</td>
			<td>Quantity</td>
		</tr>
		@foreach($task->orderVendorProducts as $key => $product)
		<tr>
			<th scope="row">{{ ++$key }}</th>
			<td><?= !empty($product->product->product) ? $product->product->product->title:'' ?> </td>
			<td>{{ $product->quantity }}</td>
		</tr>
		@endforeach
	</tbody>
</table>