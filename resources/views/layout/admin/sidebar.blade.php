<?php 
	$users_system = DB::table('users_system')->where('users_system_id', session('id'))->get()->first();
	$permissions = DB::table('users_system_roles')->where('users_system_roles_id', $users_system->users_system_roles_id)->get()->first();
?>
<style>
	li.mm-active > a i{
		color: #8000FF;
	}
</style>
 
<div class="deznav">
	<div class="deznav-scroll">
		<ul class="metismenu" id="menu">
			<?php if($permissions->dashboard == 'Yes'){ ?>
			<li>
				<a href="{{ url('admin/dashboard')  }}" aria-expanded="true">	
					<i class="fa fa-th-large" aria-hidden="true"></i>
					<span class="nav-text"> Dashboard</span>
				</a>
			</li>
			<?php } ?>

			<?php if($permissions->users_customers == 'Yes' || $permissions->users_partners == 'Yes'){ ?>
			<li>
				<a class="has-arrow ai-icon" aria-expanded="false" href="javascript:void()">
					<i class="fa fa-users"></i>
					<span class="nav-text"> Manage Users</span>
				</a>

				<ul aria-expanded="false">
					<?php if($permissions->users_customers == 'Yes'){ ?>
			        <li><a href=" {{ url('admin/users_customers') }}">Customers</a></li>	
			        <?php } ?>
					
					<?php if($permissions->users_partners == 'Yes'){ ?>
			        <li><a href=" {{ url('admin/users_partners') }}">Partners</a></li>	
			        <?php } ?>
				</ul>
			</li>
			<?php } ?>

			<?php if($permissions->users_customers_support == 'Yes'){ ?>
			<li>
				<a href="{{ url('admin/support') }}"  aria-expanded="true">
					<i class="fa fa-phone"></i>
					<span class="nav-text"> Customer Support</span>
				</a>
			</li>	
			<?php } ?>

			<?php if($permissions->survey_categories == 'Yes' || $permissions->survey_list == 'Yes' || $permissions->survey_rewards == 'Yes'){ ?>
			<li>
				<a class="has-arrow ai-icon" aria-expanded="false" href="javascript:void()">
					<i class="fa fa-shopping-cart"></i>
					<span class="nav-text"> Survey Settings</span>
				</a>

				<ul aria-expanded="false">
					<?php if($permissions->survey_list == 'Yes'){ ?>
			        <li><a href=" {{ url('admin/survey_list') }}">Survey List</a></li>	
			        <?php } ?>
					
					<?php if($permissions->survey_rewards == 'Yes'){ ?>
			        <li><a href=" {{ url('admin/survey_rewards') }}">Survey Rewards</a></li>	
			        <?php } ?>

					<?php if($permissions->survey_categories == 'Yes'){ ?>
			        <li><a href=" {{ url('admin/survey_categories') }}">Survey Categories</a></li>	
			        <?php } ?>
				</ul>
			</li>
			<?php } ?>

			<?php if($permissions->blogs == 'Yes'){ ?>
			<li>
				<a href="{{ url('admin/blogs')  }}" aria-expanded="true">	
					<i class="fa fa-list" aria-hidden="true"></i>
					<span class="nav-text"> Eco Blogs</span>
				</a>
			</li>
			<?php } ?>

			<?php if($permissions->system_settings == 'Yes' || $permissions->users_system == 'Yes' || $permissions->users_system_roles == 'Yes' || $permissions->system_settings == 'Yes' || $permissions->system_countries == 'Yes'){ ?>
			<li>
				<a class="has-arrow ai-icon" aria-expanded="false" href="javascript:void()">
					<i class="fa fa-gears"></i>
					<span class="nav-text"> System Settings</span>
				</a>

				<ul aria-expanded="false">
					<?php if($permissions->system_countries == 'Yes'){ ?>
			        <li><a href=" {{ url('admin/system_countries') }}">Countries</a></li>	
			        <?php } ?>

			        <?php if($permissions->system_settings == 'Yes'){ ?>
			        <li><a href=" {{ url('admin/system_settings') }}">Settings</a></li>	
			        <?php } ?>
					
					<?php if($permissions->users_system == 'Yes'){ ?>
			        <li><a href=" {{ url('admin/users_system') }}">Admin Users</a></li>	
			        <?php } ?>

					<?php if($permissions->users_system_roles == 'Yes'){ ?>
			        <li><a href=" {{ url('admin/users_system_roles') }}">Admin Users Roles</a></li>	
			        <?php } ?>

			        <?php if($permissions->partners_images == 'Yes'){ ?>
			        <li><a href=" {{ url('admin/partners_images') }}">Patners Images</a></li>	
			        <?php } ?>
				</ul>
			</li>
			<?php } ?>

			<?php if($permissions->account_settings == 'Yes'){ ?>
			<li>
				<a href="{{ url('admin/account_settings')  }}" aria-expanded="true">	
					<i class="fa fa-wrench"></i>
					<span class="nav-text"> General Settings</span>
				</a>
			</li>
			<?php } ?>
		</ul>
    </div>
</div>