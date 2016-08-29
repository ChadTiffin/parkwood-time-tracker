@extends("master")
@section("content")
<div class="main log-list" role="main">

<div class="container-fluid">

	<h1>Logs Listing</h1>
	<div class="row">
		<div class="col-sm-4">
			<div>
				{{ Form::open(array("class" => "form-horizontal", "id" => "filter-form")) }}
					<h2>Filtering</h2>
			
					<h3>Date Range</h3>
					
						<label class="control-label col-sm-3">From </label>
						<div class='input-group date'>
							<input type='text' class="form-control" id='filter-from-date' value="{{Request::segment(2)}}"/>
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-calendar"></span>
							</span>
						</div>

						<label class="control-label col-sm-3">To </label>
						<div class='input-group date '>
							<input type='text' class="form-control" id='filter-to-date' value="{{Request::segment(3)}}"/>
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-calendar"></span>
							</span>
						</div>
					

					<h3>Shift Length</h3>

					<div class="form-group">
						
						<label class="control-label col-sm-4">Min (Hrs) </label>
						<div class="col-sm-8">
							<input type='number' min="0" step="0.1" class="form-control" id="filter-min-shift" value="{{Request::segment(4)}}"/>
						</div>

						<label class="control-label col-sm-4">Max (Hrs) </label>
						<div class="col-sm-8">
							<input type='number' min="0" step="0.1" class="form-control" id="filter-max-shift" value="{{Request::segment(5)}}"/>
						</div>
						
					</div>

					<p class="pull-right">
						<input type="button" value="Clear Filter" class="btn btn-default" id='clear-filter'>
						<input type="submit" value="Filter" class="btn btn-primary">
					</p>
				{{ Form::close() }}
			</div>
			<div style="clear:both"></div>
			<div>

				<h2>Send Email Report</h2>

				<input type="button" value="Send Email Report..." class="btn btn-primary btn-lg" id="send-email-report">
			</div>
		</div>

		<div class='col-sm-8'>
			<h2>Logs</h2>

			<table class="table log-list">
				<thead>
					<tr>
						<th>Date</th>
						<th>Clocked In</th>
						<th>Clocked Out</th>
						<th style="width:150px;text-align:right;">Total Time (hrs)</th>
						<th style="width:120px;">Edit/Delete</th>
					</tr>
				</thead>
				<tbody>
				@if (count($logs) == 0)

					<tr>
						<td colspan="5">No entries found.</td>
					</tr>
				@else
					@foreach ($logs as $log)
						
						<tr data-id='{{$log->id}}'>
							<td class="log-date">{{{format_datetime($log->clocked_in)}}}</td>
							<td class="clocked_in" data-datetime="{{{$log->clocked_in}}}"><?=format_datetime($log->clocked_in,"time")?></td>
							<td style="text-align:left;" class="clocked_out" data-datetime="{{{$log->clocked_out}}}">
								<?php
								if ($log->clocked_out == null) {
									echo "[in progress]";
								}
								else {
									echo format_datetime($log->clocked_out,"time");
								} ?>
							</td>
							<td style="text-align:right;" class="total">
								<?php
								if ($log->clocked_out == null) {
									echo "...";
								}
								else {
									echo abs(round($log->shift_total/60,2));
								} ?>
							</td>
							<td class='edit-controls' data-id='<?=$log->id?>'>
								<button class="btn btn-warning btn-xs edit"><img src="{{url()}}/images/edit-icon.png"></button>
								<button class='btn btn-danger btn-sm delete'>- Del</button>
							</td>
						</tr>

					@endforeach
				@endif
				</tbody>
				<tfoot>
					<tr>
						<th colspan="3" style="text-align:right;">Total</th>
						<td style="text-align:right;"><strong><?=$query_total?></strong></td>
						<td colspan="2"></td>
					</tr>
				</tfoot>
			</table>

		</div>


<!-- Edit Log Modal -->
<div id="edit-log" class="modal fade">
	<div class="modal-dialog">
		{{ Form::open(array("class" => "modal-content")) }}
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Edit Log</h4>
			</div>
			<div class="modal-body">

				<label class="control-label col-sm-3">Clocked In </label>
				<p class="col-sm-9">
				<input type='text' class="form-control" id='edit-clocked-in' name="edit-clocked-in">
				</p>

				<label class="control-label col-sm-3">Clocked Out </label>
				<p class="col-sm-9">
				<input type='text' class="form-control" id='edit-clocked-out' name="edit-clocked-out">
				</p>

				<input type="hidden" name="id" value="">

				<div style="clear:both"></div>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary" id="save-edit">Save Changes</button>
			</div>
		{{ Form::close() }}
	</div>
</div>

<!-- Send Email Report Modal -->
<div id="email-report" class="modal fade">
	<div class="modal-dialog">
		{{ Form::open(array("class" => "modal-content form-horizontal")) }}
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Send Email Report</h4>
			</div>
			<div class="modal-body">

				<div class="form-group">
					<label class="control-label col-sm-3">Report Type</label>
					
					<div class="col-sm-8">
						<select class="form-control">
							<option>Hours Total Only</option>
							<option>Full Breakdown</option>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-sm-3">Email To:</label>
					
					<div class="col-sm-8">
						<input type="text" value="" class="form-control">
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-sm-3">Email From</label>
					
					<div class="col-sm-8">
						<input type="text" value="chad@chadtiffin.com" class="form-control">
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-sm-3">Email Subject</label>
					
					<div class="col-sm-8">
						<input type="text" value="Hours Report" class="form-control">
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-sm-3">Message</label>
					
					<div class="col-sm-8">
						<textarea class="form-control"></textarea>
					</div>
				</div>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary" id="save-edit">Send</button>
			</div>
		{{ Form::close() }}
	</div>
</div>
@stop

@section("js")
<script>
	$(document).ready(function(){

		$('.edit-controls .delete').click(function(){

			var log_id = $(this).parent().attr("data-id");

			var action = BASE_URL + "/delete-log";

			$("#confirmation-modal").find(".modal-body").html("Are you sure you want to delete this record?\
				<input type='hidden' name='id' value='" + log_id + "'>");
			$('#confirmation-modal form').attr("action",action).attr("method","post");
			$("#confirmation-modal").modal("show");

		});

		$('#confirmation-modal form').submit(function(e){
			e.preventDefault();

			var action = $(this).attr("action");
			var log_id = $(this).find("input[name=id]").val();

			$.post(action,"id=" + log_id,function(){
				$("#confirmation-modal").modal("hide");
				$('.log-list tr[data-id=' + log_id + "]").remove();
			});
		});

		$('.edit-controls .edit').click(function(){
			var log_id = $(this).parent().attr("data-id");

			//Fill in details
			var clocked_in = $(this).parent().parent().find(".clocked_in").attr("data-datetime");
			var clocked_out = $(this).parent().parent().find(".clocked_out").attr("data-datetime");

			$("#edit-log #edit-clocked-in").val(clocked_in);
			$("#edit-log #edit-clocked-out").val(clocked_out);
			$("#edit-log input[name=id]").val(log_id);

			$("#edit-log form").attr("action",BASE_URL + "/edit");

			$("#edit-log").modal("show");
		});

		$("#edit-log form").submit(function(e){
			e.preventDefault();

			var action = $(this).attr("action");
			var form_data = $(this).serialize();

			var log_id = $(this).find("input[name=id]").val();

			$.post(action,form_data,function(log){
				$("#edit-log").modal("hide");

				//data returned is json of row
				var row = $(".log-list tr[data-id=" + log_id + "]");

				row.find(".log-date").html(log.date);
				row.find(".clocked_in").html(log.clocked_in);
				row.find(".clocked_out").html(log.clocked_out);
				row.find(".total").html(log.total);
				
			},'json');
		});

		$('#filter-form').submit(function(e){
			e.preventDefault();

			var from_date = $("#filter-from-date").val();
			var to_date = $("#filter-to-date").val();
			var min_shift = $("#filter-min-shift").val();
			var max_shift = $("#filter-max-shift").val();

			window.location.href = BASE_URL + "/logs/" + from_date + "/" + to_date + "/" + min_shift + "/" + max_shift;
		});

		$("#filter-form #clear-filter").click(function(){
			window.location.href = BASE_URL + "/logs/";
		});

		$(".date input").click(function(){
			//alert('test');
			$(this).parent().find('span.input-group-addon').trigger("click");
		});

		$("#send-email-report").click(function(){
			$("#email-report").modal("show");
		});
		
	});
</script>
@stop
