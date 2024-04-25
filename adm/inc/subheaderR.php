<div class="kt-subheader__toolbar">
	<div class="kt-subheader__wrapper">

		<?php if($u_role['add_access'] === '1') echo $addBtn; ?>

		<?php if (in_array('messages', $user_permissions)): ?>
		<div class="dropdown dropdown-inline" data-toggle="kt-tooltip" data-original-title="<?php echo $words['sendmsg']; ?>">
			<a data-toggle="modal" data-target="#sendMsgSticky" class="btn <?php if(SCRIPTNAME != 'messages') echo 'btn-icon'; ?> btn btn-label btn-label-warning btn-bold">
				<i class="flaticon2-send-1"></i>
				<?php if(SCRIPTNAME == 'messages') echo $words['sendmsg']; ?>
			</a>
		</div>
		<?php endif; ?>

		<?php if (($u_role['add_access'] === '1' || $u_role['value'] === 'teacher') && in_array('groups', $user_permissions)) : ?>
		<div class="dropdown dropdown-inline" data-toggle="kt-tooltip" data-original-title="<?php echo $words['attendance']; ?>">
			<a data-toggle="modal" data-target="#addAttendanceModal" class="btn btn-icon btn btn-label btn-label-primary btn-bold">
				<i class="flaticon-user-add"></i>
			</a>
		</div>
		<?php endif;

		if(in_array('payments', $user_permissions)){ ?>
		<div class="dropdown dropdown-inline" data-title="" trigger="hover" data-toggle="kt-tooltip" data-original-title="<?php echo $words['qarzdorlar']; ?>">
			<a data-toggle="modal" data-target="#debtorsModal" class="btn btn-icon btn btn-label btn-label-success btn-bold">
				<i class="fas fa-money-bill-wave"></i>
			</a>
		</div>
		<?php }

		if($u_role['value'] === 'teacher' && $u_role['add_access'] === '1'){
			echo '<div class="dropdown dropdown-inline" data-toggle="kt-tooltip" data-original-title="'.$words['addelon'].'">
				<a data-toggle="modal" data-target="#announcementAddModal" class="btn btn btn-label btn-label-danger btn-bold">
					<i class="flaticon2-sms p-0"></i>
				</a>
			</div>';
		}

		if($u_role['add_access'] !== '1'){
			echo '<div class="dropdown dropdown-inline">
				<a data-toggle="modal" data-target="#eventAddModal" class="btn btn btn-label btn-label-success btn-bold">
					'.$words['addevents'].'
				</a>
			</div>';
		}

		if($u_role['add_access'] === '1'): ?>
		<div class="dropdown dropdown-inline" data-toggle="kt-tooltip" data-original-title="<?php echo $words['add']; ?>">
			<a href="#" class="btn btn-icon btn btn-label btn-label-brand btn-bold" data-toggle="dropdown" data-offset="0px,0px" aria-haspopup="true" aria-expanded="false">
				<i class="flaticon2-add-1"></i>
			</a>
			<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
				<ul class="kt-nav kt-nav--active-bg" role="tablist">

					<?php if(in_array('groups', $user_permissions)): ?>
					<li class="kt-nav__item">
						<a class="kt-nav__link" data-toggle="modal" data-target="#addGroupModal">
							<i class="kt-nav__link-icon flaticon2-supermarket"></i>
							<span class="kt-nav__link-text"><?php echo $words['addguruh']; ?></span>
						</a>
					</li>
					<?php endif; ?>

					<?php if(in_array('rooms', $user_permissions)): ?>
					<li class="kt-nav__item">
						<a class="kt-nav__link" data-toggle="modal" data-target="#addRoomModal">
							<i class="kt-nav__link-icon flaticon2-supermarket"></i>
							<span class="kt-nav__link-text"><?php echo $words['addxona']; ?></span>
						</a>
					</li>
					<?php endif; ?>

					<?php if(in_array('payments', $user_permissions)): ?>
					<li class="kt-nav__item">
						<a class="kt-nav__link" data-toggle="modal" data-target="#paymentModal">
							<i class="kt-nav__link-icon flaticon2-supermarket"></i>
							<span class="kt-nav__link-text"><?php echo $words['addtulov']; ?></span>
						</a>
					</li>
					<?php endif; ?>

					<?php if(in_array('subjects', $user_permissions)): ?>
					<li class="kt-nav__item">
						<a class="kt-nav__link" data-toggle="modal" data-target="#addSubjectModal">
							<i class="kt-nav__link-icon flaticon2-supermarket"></i>
							<span class="kt-nav__link-text"><?php echo $words['addfan']; ?></span>
						</a>
					</li>
					<?php endif; ?>

					<?php if(in_array('expenses', $user_permissions)): ?>
					<li class="kt-nav__item">
						<a class="kt-nav__link" data-toggle="modal" data-target="#harajatModal">
							<i class="kt-nav__link-icon flaticon2-shopping-cart"></i>
							<span class="kt-nav__link-text"><?php echo $words['addharajat']; ?></span>
						</a>
					</li>
					<!-- <li class="kt-nav__item">
						<a href="posts?add" class="kt-nav__link">
							<i class="kt-nav__link-icon flaticon2-website"></i>
							<span class="kt-nav__link-text"><?php echo $words['addnews']; ?></span>
						</a>
					</li> -->
					<?php endif; ?>

					<?php if(in_array('calendar', $user_permissions) && $noneed): ?>
					<li class="kt-nav__item">
						<a class="kt-nav__link" data-toggle="modal" data-target="#eventAddModal">
							<i class="kt-nav__link-icon flaticon-event-calendar-symbol"></i>
							<span class="kt-nav__link-text"><?php echo $words['addevents']; ?></span>
						</a>
					</li>
					<?php endif; ?>

					<?php if(in_array('posts', $user_permissions)): ?>
					<li class="kt-nav__item">
						<a class="kt-nav__link" data-toggle="modal" data-target="#announcementAddModal">
							<i class="kt-nav__link-icon flaticon2-sms"></i>
							<span class="kt-nav__link-text"><?php echo $words['addelon']; ?></span>
						</a>
					</li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
		<?php endif; ?>

		<?php if ($u_role['add_access'] === '1' && in_array('users', $user_permissions)): ?>
			<div class="dropdown dropdown-inline" data-toggle="kt-tooltip" data-original-title="<?php echo $words['adduser']; ?>">
				<a id="kt_quick_panel_toggler_btn" class="btn btn-icon btn btn-label btn-label-danger btn-bold">
					<i class="flaticon-add"></i>
				</a>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php unset($headerSettings);
