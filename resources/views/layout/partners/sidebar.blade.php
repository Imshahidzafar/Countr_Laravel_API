<style>
	li.mm-active > a i{
		color: #8000FF;
	}
</style>
 
<div class="deznav">
	<div class="deznav-scroll">
		<ul class="metismenu" id="menu">
			<li>
				<a href="{{ url('partners/dashboard')  }}" aria-expanded="true">	
					<i class="fa fa-th-large" aria-hidden="true"></i>
					<span class="nav-text"> Dashboard</span>
				</a>
			</li>

			<li>
				<a href="{{ url('partners/survey_list')  }}" aria-expanded="true">	
					<i class="fa fa-list" aria-hidden="true"></i>
					<span class="nav-text"> Survey List</span>
				</a>
			</li>

			<li>
				<a href="{{ url('partners/account_settings')  }}" aria-expanded="true">	
					<i class="fa fa-wrench"></i>
					<span class="nav-text"> General Settings</span>
				</a>
			</li>
		</ul>
    </div>
</div>