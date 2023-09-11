<style>
	li.mm-active > a i{
		color: #8000FF;
	}
</style>
 
<div class="deznav">
	<div class="deznav-scroll">
		<ul class="metismenu" id="menu">
			<li>
				<a href="{{ url('users/online_survey/'.session('s_id'))  }}" aria-expanded="true">	
					<i class="fa fa-th-large" aria-hidden="true"></i>
					<span class="nav-text"> Survey Qs</span>
				</a>
			</li>

			<li>
				<a href="{{ url('users/account_settings')  }}" aria-expanded="true">	
					<i class="fa fa-wrench"></i>
					<span class="nav-text"> General Settings</span>
				</a>
			</li>
		</ul>
    </div>
</div>