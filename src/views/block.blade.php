@push('scripts')
    <script src="{{ asset('vendor/adminamazing/assets/plugins/sparkline/jquery.sparkline.min.js') }}"></script>
	<!-- chartist chart -->
	<script src="{{ asset('vendor/adminamazing/assets/plugins/chartist-js/dist/chartist.min.js') }}"></script>
	<script src="{{ asset('vendor/adminamazing/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js') }}"></script>
    <script>
        $(function(){
        	// ============================================================== 
		    // sales difference
		    // ==============================================================
		    new Chartist.Pie('.ct-chart', {
		    	series: [{{$data_chart}}]
		    },{
		        donut: true, donutWidth: 20, startAngle: 0, showLabel: false
		    });
        })
    </script>
@endpush
<div class="card-block">
    <h4 class="card-title">Служба поддержки</h4>
    <div class="d-flex flex-row">
        <div class="align-self-center">
            <span class="display-6">Новых: {{$feedback_data['New']}}</span>
            <h6 class="text-muted">(Всего: {{$all}})</h6>
        </div>
        <div class="ml-auto">
            <div class="ct-chart" style="width:120px; height: 120px;"></div>
        </div>
    </div>
</div>