<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-4">
                {{ $global_setting_year }} © {{env('APP_NAME')}}. version {{ $global_setting_version }}
                {{-- <script>document.write(new Date().getFullYear())</script> © Document Tracking System --}}
            </div>
            <div class="col-sm-4" style="text-align: center;">        
                {{-- <p id="current_datetime_footer"></p>see footer script --}}
                <span id='dynamic_datetime_footer'>Dynamic Current Date and Time</span>                
            </div>
            <div class="col-sm-4">
                <div class="text-sm-right d-none d-sm-block">
                    {{ $global_setting_company_name }}
                </div>
            </div>
        </div>
    </div>
</footer>