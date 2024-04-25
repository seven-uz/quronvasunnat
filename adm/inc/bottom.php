<!-- To top -->
<div id="kt_scrolltop" class="kt-scrolltop"><i class="fa fa-arrow-up"></i></div>

<?php $bottomConfig = ["settingsContent" => false, "quickRegister" => true, ]; ?>

<?php if ($bottomConfig['settingsContent'] === true): ?>
	<!-- Settings content -->
	<div id="kt_offcanvas_toolbar_quick_actions" class="kt-offcanvas-panel">
		<div class="kt-offcanvas-panel__head" kt-hidden-height="88">
			<h3 class="kt-offcanvas-panel__title">
				<?= $words['quickactions'] ?>
			</h3>
			<a href="#" class="kt-offcanvas-panel__close" id="kt_offcanvas_toolbar_quick_actions_close"><i class="flaticon2-delete"></i></a>
		</div>
		<div class="kt-offcanvas-panel__body kt-scroll ps" style="height: 752px; overflow: hidden;">
			<div class="kt-grid-nav-v2">
				<a href="#" class="kt-grid-nav-v2__item">
					<div class="kt-grid-nav-v2__item-icon"><i class="flaticon2-box"></i></div>
					<div class="kt-grid-nav-v2__item-title">Orders</div>
				</a>
				<a href="#" class="kt-grid-nav-v2__item">
					<div class="kt-grid-nav-v2__item-icon"><i class="flaticon-download-1"></i></div>
					<div class="kt-grid-nav-v2__item-title">Uploades</div>
				</a>
				<a href="#" class="kt-grid-nav-v2__item">
					<div class="kt-grid-nav-v2__item-icon"><i class="flaticon2-supermarket"></i></div>
					<div class="kt-grid-nav-v2__item-title">Products</div>
				</a>
				<a href="#" class="kt-grid-nav-v2__item">
					<div class="kt-grid-nav-v2__item-icon"><i class="flaticon2-avatar"></i></div>
					<div class="kt-grid-nav-v2__item-title">Customers</div>
				</a>
				<a href="#" class="kt-grid-nav-v2__item">
					<div class="kt-grid-nav-v2__item-icon"><i class="flaticon2-list"></i></div>
					<div class="kt-grid-nav-v2__item-title">Blog Posts</div>
				</a>
				<a href="users?id=<?= $_SESSION['id'] ?>" class="kt-grid-nav-v2__item">
					<div class="kt-grid-nav-v2__item-icon"><i class="flaticon2-settings"></i></div>
					<div class="kt-grid-nav-v2__item-title"><?= $words['settings'] ?></div>
				</a>
			</div>
			<div class="ps__rail-x" style="left: 0px; bottom: 0px;">
				<div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
			</div>
			<div class="ps__rail-y" style="top: 0px; right: 0px;">
				<div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php if ($u_role['add_access'] === '1' && in_array('users', $user_permissions)): ?>
	<!-- Quick user add content -->
	<?php
	$roles_for_quickreg = $Query->getN('roles', ['fields' => 'id, titleuzl, titleuzk, titleru, titleen, value', 'order' => ['title'.$_COOKIE['lang']]]);
	if($roles_for_quickreg != null && $subjects['status'] != 'empty'){
		foreach ($roles_for_quickreg as $val) {
			if($val['value'] === 'pupil' || $val['value'] === 'teacher') continue;
			$reles_json[] = ['id' => $val['id'], 'title' => $val['title'.$_COOKIE['lang']]];
		}
		?><script>const rolesJson = <?php echo json_encode($reles_json); unset($reles_json); ?></script><?php
	} unset($roles_for_quickreg); ?>
	<?php if($subjects != null && $subjects['status'] != 'empty'){ foreach ($subjects as $val) { $subjects_json[] = $val; } ?>
		<script>
			const subjectsJson = <?php echo json_encode($subjects_json); unset($subjects_json); ?>
		</script>
	<?php }else{ ?>
		<script>
			const subjectsJson = {0:{"id":"null","title":"<?php echo $words['notfoundsubject'];?>"}}
		</script>
	<?php } ?>

	<div id="kt_quick_panel" class="kt-offcanvas-panel">
		<div class="kt-offcanvas-panel__nav" kt-hidden-height="77">
			<h4><?php echo $words['doregister'] ?></h4>
			<button class="kt-offcanvas-panel__close" id="kt_quick_panel_close_btn"><i class="flaticon2-delete"></i></button>
		</div>
		<div class="kt-offcanvas-panel__body">
			<div class="kt-offcanvas-panel__content kt-scroll active ps ps--active-y">
				<form id="adduserbyadm">
					<div class="form-group">
						<div class="btn-group d-flex flex-nowrap" data-toggle="buttons" id="viewMode">
							<label class="btn btn-brand btn-elevate">
								<input type="radio" class="sr-only" id="quick_register_pupil" name="type" value="3" checked />
								<span class="docs-tooltip"><?= $words['pupil'] ?></span>
							</label>
							<label class="btn btn-brand btn-elevate">
								<input type="radio" class="sr-only" id="quick_register_teacher" name="type" value="2">
								<span class="docs-tooltip"><?= $words['teacher'] ?></span>
							</label>
							<label class="btn btn-brand btn-elevate">
								<input type="radio" class="sr-only" id="quick_register_user" name="type" value="1">
								<span class="docs-tooltip"><?= $words['user'] ?></span>
							</label>
						</div>
					</div>
					<div class="form-group" data-content="select">
						<label class="required" for="quickAuthSubject"><?= $words['subjectmore'] ?>:</label>
						<div class="input-group">
							<div class="input-group-prepend mr-3" style="width: 60px;">
								<div class="input-group">
									<div>
										<input type="text" class="form-control bootstrap-touchspin-vertical-btn" min="2" max="4" name="for_group" id="touchspin_vertical" readonly>
									</div>
								</div>
							</div>
							<select class="form-control kt_selectpicker" name="subject_id[]" id="quickAuthSubject" data-size="6" title="<?php echo $words['choose'] ?>" multiple>
								<?php if($subjects['status'] != 'empty'){ foreach ($subjects as $row) {
									echo '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
								} } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="required" for="surname"><?= $words['surname'] ?>:</label>
						<input type="text" class="form-control" name="surname">
					</div>
					<div class="form-group">
						<label class="required" for="name"><?= $words['name'] ?>:</label>
						<input type="text" class="form-control" name="name">
					</div>
					<div class="form-group">
						<label class="required" for="tel"><?= $words['tel'] ?>:</label>
						<input type="text" class="form-control user-phone" name="phone">
					</div>
					<div class="form-group">
						<label><?= $words['comment'] ?>:</label>
						<textarea class="form-control" name="comment"></textarea>
					</div>

					<div class="form-group form-group-xs row">
						<label class="col-8 col-form-label"><?php echo $words['password'] ?>:</label>
						<div class="col-4 kt-align-right">
							<span class="kt-switch kt-switch--sm kt-switch--success">
								<label>
									<code>121314</code>
								</label>
							</span>
						</div>
					</div>

					<div class="form-group text-right">
						<button class="btn btn-brand btn-upper btn-sm btn-bold mt-4" type="submit"><?php echo $words['save']; ?></button>
					</div>

				</form>
				<div class="ps__rail-x" style="left: 0px; bottom: 0px;">
					<div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
				</div>
				<div class="ps__rail-y" style="top: 0px; right: 5px;">
					<div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>

<!--============================== Modal windows START ==============================-->

<?php if (($u_role['view_access'] === '1' || $u_role['add_access'] === '1') && in_array('payments', $user_permissions)) : ?>
	<!-- Debtors Modal -->
	<div class="modal fade" id="debtorsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" style="display: none;" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content modal-md">
				<div class="modal-header p-3">
					<h5 class="modal-title">
						<?= $words['jamiqarzdorlik'] . ' ( ' . nf($debtorsAmount) . ' )'; ?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body overflow-auto">
					<?php if ($debtorsAmount > 0) { ?>
						<table class="table table-hover table-responsive-sm" id="modaltable" role="grid" data-toggle-selector=".footable">
							<thead class="thead-light">
								<tr>
									<th class="bg-info text-white">№</th>
									<th class="bg-info text-white" align="left"><?= $words['fio'] ?></th>
									<!-- <th class="bg-info text-white" align="left"><?= $words['guruhi'] ?></th> -->
									<th class="bg-info text-white text-center"><?= $words['tulaganmagansumma'] ?></th>
									<?php if ($u_role['add_access'] === '1'){?><th class="bg-info text-white text-center"><?= $words['pay_quickly'] ?></th><?php } ?>
								</tr>
							</thead>
							<tbody>
								<?php
								// $user_ids = [];
								// foreach ($pupils_debtors as $key => $row) {

								// 	if ($row['need_pay'] > 0) {

								// 		if(in_array($row['user_id'], $user_ids)) continue;

								// 		array_push($user_ids, $row['user_id']);

								// 		echo '<tr>
								// 			<td>' . ++$numberDebtorsModal . '</td>
								// 			<td align="left"><a target="_blank" href="users?id=' . $row['user_id'] . '">' . $row['surname'] . " " . $row['name'] . '</a></td>
								// 			<td align="left"><a target="_blank" href="groups?id=' . $row['group_id'] . '">' . $row['group_title'] . '</a></td>
								// 			<td align="center">' . nf($pupils_need_pays[$row['user_id']]['need_pay']) . '</td>';
								// 			if ($u_role['add_access'] === '1'){
								// 			echo '<td class="text-center">
								// 				<span class="btn btn-brand btn-upper btn-sm btn-sm payDebt"
								// 					data-payments=' . json_encode($pupils_need_pays[$row['user_id']]['payments']) . '
								// 					data-pupil_ids=' . json_encode($pupils_need_pays[$row['user_id']]['pupil_ids']) . '
								// 					data-payment-username="' . $row['surname'] . " " . $row['name'] . '"
								// 					data-payment-amount="' . nf($pupils_need_pays[$row['user_id']]['need_pay']) . '"
								// 				>
								// 					<i class="fas fa-dollar-sign"></i>
								// 					<i class="fas fa-arrow-right mr-0"></i>
								// 				</span>
								// 			</td>';
								// 			}
								// 		echo '</tr>';
								// 	}
								// }
								foreach ($debtors_new as $key => $row) {
									$left_amount = 0;
									$data_payment = [];
									foreach($row['groups'] as $pupil_id => $amon){
										$left_amount += array_sum($amon['left']);
										foreach ($amon['left'] as $k => $v) {
											if($v > 0){
												$data_payment[$pupil_id][$amon['id']][$k] = $v;
											}
										}
									}
									if ($left_amount > 5) {
										echo '<tr>
											<td>' . ++$numberDebtorsModal . '</td>
											<td align="left"><a target="_blank" href="users?id=' . $key . '">' . $row['fio'] . '</a></td>
											<td align="center">'.nf($left_amount).'</td>';
											if ($u_role['add_access'] === '1'){
											echo '<td class="text-center">
												<span class="btn btn-brand btn-upper btn-sm btn-sm payDebt"
													data-payments=' . json_encode($data_payment) . '
													data-payment-username="' . $row['fio'] . '"
													data-payment-amount="' . nf($left_amount) . '"
												>
													<i class="fas fa-dollar-sign"></i>
													<i class="fas fa-arrow-right mr-0"></i>
												</span>
											</td>';
											}
										echo '</tr>';
									}
								}
								?>
							</tbody>
						</table>
					<?php } else {
						echo '<h3>' . $words['notdebtors'] . '!</h3>';
					} ?>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php if ($u_role['add_access'] === '1' && in_array('payments', $user_permissions)): ?>
	<!-- Payments Modal -->
	<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content modal-sm">
				<div class="modal-header p-3">
					<h5 class="modal-title"><?= $words['addtulov'] ?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="addtulov">
						<div class="form-group row">
							<label class="col-4 col-form-label required"><?= $words['pupil'] ?></label>
							<div class="col-8">
								<select name="pupil_id" id="paymentModalUser" class="form-control kt_selectpicker" title="<?= $words['choose'] ?>" data-live-search="true" tabindex="-98">
									<?php
									$pupils_for_add_payment = $Query->getN('pupils p', [
										'fields' => 'p.id, p.user_id, CONCAT(u.surname, " ", u.name) as fio',
										'join' => [['table' => 'users u', 'on' => 'u.id = p.user_id']],
										'where' => [['column' => 'p.active', 'value' => "'1'"]],
										'order' => ['fio'],
									]);
									if($pupils_for_add_payment['status'] != 'empty'){ foreach ($pupils_for_add_payment as $k => $row) {
										if($row['user_id'] == $pupils_for_add_payment[($k-1)]['user_id']) continue; ?>
										<option value="<?= $row['id'] ?>"><?= $row['fio'] ?></option>
									<?php } } ?>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-4 col-form-label required"><?= $words['guruhi'] ?></label>
							<div class="col-8" id="resultOption">
								<select class="form-control kt_selectpicker">
									<option disabled selected><?= $words['choose_pupil'] ?></option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-4 col-form-label required"><?= $words['summa'] ?></label>
							<div class="col-8">
								<div class="position-relative">
									<input class="form-control text-left" type="text" name="summa" id="summa">
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-4 col-form-label required"><?= $words['payment_for'] ?></label>
							<div class="col-8">
								<input type="text" class="form-control monthpicker w-100" name="day" picker-type="month" value="<?= date("m.Y") ?>" readonly>
							</div>
						</div>
						<div class="form-group row form-group-last">
							<label class="col-4 col-form-label"><?= $words['comment'] ?></label>
							<div class="col-8">
								<textarea class="form-control" name="comment"></textarea>
							</div>
						</div>
						<div class="text-right">
							<button class="btn btn-brand btn-upper btn-sm btn-sm btn-bold mt-3"><?= $words['save'] ?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php if ($u_role['add_access'] === '1' && in_array('groups', $user_permissions)) : ?>
	<!-- Add Group Modal -->
	<div class="modal fade" id="addGroupModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content modal-sm">
				<div class="modal-header p-3">
					<h5 class="modal-title"><?= $words['addguruh'] ?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="addGroupForm" enctype="multipart/form-data">
						<div class="form-group row">
							<label class="col-5 col-form-label required"><?php echo $words['nomi'] ?>:</label>
							<div class="col-7">
								<input class="form-control" type="text" name="title" />
							</div>
						</div>
						<div class="form-group row">
							<label class="col-5 col-form-label required"><?php echo $words['fani'] ?>:</label>
							<div class="col-7">
								<select class=" form-control kt_selectpicker" id="add_group_subject_list" name="subject_id" title="<?php echo $words['choose'] ?>">
									<?php if($subjects['status'] != 'empty'){
										foreach ($subjects as $row) { ?>
											<option value="<?php echo $row['id'] ?>"><?php echo $row['title'] ?></option>
										<?php }
									} ?>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-5 col-form-label required"><?php echo $words['teacher'] ?>:</label>
							<div class="col-7" id="add_group_subject_teachers_list">
								<select class=" form-control kt_selectpicker" name="teacher_id">
									<option selected disabled><?php echo $words['choose_subject'] ?></option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-5 col-form-label required"><?php echo $words['xonasi'] ?>:</label>
							<div class="col-7">
								<select class="form-control kt_selectpicker" name="room_id" title="<?php echo $words['choose'] ?>">
									<?php if($rooms['status'] != 'empty'){
											foreach ($rooms as $row) { ?>
										<option value="<?php echo $row['id'] ?>"><?php echo $row['title'] ?></option>
									<?php } } ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="required"><?php echo $words['day'] ?>:</label>
							<div class="weekcheckbox">
								<?php
								$dayNames = array(1 => "du", 2 => "se", 3 => "chor", 4 => "pay", 5 => "ju", 6 => "sha", 7 => "yak");
								for ($i = 1; $i < 8; $i++) {
									echo '<input class="weekch" type="checkbox" id="dayCheck_' . $i . '" name="day[]" value="' . $i . '" />
									<label class="weekl" for="dayCheck_' . $i . '">' . $words[$dayNames[$i]] . '</label>';
								}
								?>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-5 col-form-label required"><?php echo $words['opened_date'] ?>:</label>
							<div class="col-7">
								<input class="form-control datepicker w-100" type="text" name="opened_date" value="<?php echo date("d.m.Y", strtotime($now));?>" readonly />
							</div>
						</div>
						<div class="form-group row">
							<label class="col-5 col-form-label required"><?php echo $words['time'] ?>:</label>
							<div class="col-7">
								<input class="form-control timepicker" type="text" name="time" value="11:00:00" readonly />
							</div>
						</div>
						<div class="form-group row">
							<label class="col-5 col-form-label required"><?php echo $words['price'] ?>:</label>
							<div class="col-7">
								<input class="form-control text-left numberFormat" type="text" name="price" />
							</div>
						</div>
						<div class="form-group row">
							<label class="col-5 col-form-label"><?php echo $words['teaching_days'] ?>:</label>
							<div class="col-7">
								<input class="form-control" type="number" min="0" max="31" name="teaching_days" value="0" />
							</div>
						</div>
						<div class="form-group row">
							<label class="col-5 col-form-label"><?php echo $words['foizi'] ?>:</label>
							<div class="col-7">
								<input class="form-control" type="number" name="percent" />
							</div>
						</div>
						<div class="form-group row">
							<label class="col-5 col-form-label"><?php echo $words['info'] ?>:</label>
							<div class="col-7">
								<textarea class="form-control" name="info" rows="3"></textarea>
							</div>
						</div>
						<div class="form-group-last row">
							<label class="col-5 col-form-label"><?php echo $words['status'] ?>:</label>
							<div class="col-7">
								<span class="kt-switch kt-switch--icon-check kt-switch--green">
									<label>
										<input type="checkbox" name="active" checked />
										<span></span>
									</label>
								</span>
							</div>
						</div>
						<div class="text-right">
							<button class="btn btn-brand btn-upper btn-sm" type="submit"><?php echo $words['save'] ?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php if ($u_role['add_access'] === '1' && in_array('subjects', $user_permissions)) : ?>
	<!-- Add Subject Modal -->
	<div class="modal fade" id="addSubjectModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content modal-sm">
				<div class="modal-header p-3">
					<h5 class="modal-title"><?= $words['addfan'] ?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="addSubjectForm" enctype="multipart/form-data">
						<div class="form-group row">
							<label class="col-3 col-form-label required"><?php echo $words['nomi'] ?>:</label>
							<div class="col-9">
								<input class="form-control" type="text" name="title" />
							</div>
						</div>
						<div class="form-group row">
							<label class="col-3 col-form-label"><?php echo $words['info'] ?>:</label>
							<div class="col-9">
								<textarea class="form-control" name="info" rows="3"></textarea>
							</div>
						</div>
						<div class="form-group-last row">
							<label class="col-3 col-form-label"><?php echo $words['status'] ?>:</label>
							<div class="col-9">
								<input type="hidden" value="<?php echo $editRow['active'] ?>">
								<span class="kt-switch kt-switch--icon-check kt-switch--green">
									<label>
										<input type="checkbox" name="active" checked>
										<span></span>
									</label>
								</span>
							</div>
						</div>
						<div class="col-12 text-right">
							<button class="row btn btn-brand btn-upper btn-sm" type="submit"><?php echo $words['save'] ?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php if ($u_role['add_access'] === '1' && in_array('rooms', $user_permissions)) : ?>
	<!-- Add Room Modal -->
	<div class="modal fade" id="addRoomModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content modal-sm">
				<div class="modal-header p-3">
					<h5 class="modal-title"><?= $words['addxona'] ?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="addXonaForm">
						<div class="form-group row">
							<label class="col-3 col-form-label required"><?php echo $words['nomi'] ?></label>
							<div class="col-9">
								<input class="form-control" type="text" name="name">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-3 col-form-label required"><?php echo $words['capacity'] ?></label>
							<div class="col-9">
								<input class="form-control" type="number" name="capacity" min="1" max="500">
							</div>
						</div>
						<div class="form-group-last row">
							<label class="col-3 col-form-label"><?php echo $words['active'] ?>:</label>
							<div class="col-9">
								<span class="kt-switch kt-switch--icon-check kt-switch--green">
									<label>
										<input type="checkbox" name="active" checked>
										<span></span>
									</label>
								</span>
							</div>
						</div>
						<div class="text-right">
							<button type="submit" class="btn btn-brand btn-upper btn-sm"><?php echo $words['save'] ?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php if ($u_role['add_access'] === '1' && in_array('expenses', $user_permissions)):
	$expenses_cats = $Query->getN('expenses_cats', ['fields' => 'id, title', 'order' => ['title']]);?>
	<!-- Add Expenses Modal -->
	<div class="modal fade" id="harajatModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content modal-sm">
				<div class="modal-header p-3">
					<h5 class="modal-title"><?= $words['addharajat'] ?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="addExpenseForm">
						<div class="form-group row">
							<label class="col-4 col-form-label required"><?= $words['expense'] ?></label>
							<div class="col-8">
								<div class="input-group">
									<select name="type_id" class="form-control kt_selectpicker expense_type" title="<?php echo $words['choose'] ?>" data-live-search="true" tabindex="-98">
										<?php
										if($expenses_cats['status'] != 'empty'){ foreach ($expenses_cats as $row) {
											echo '<optgroup label="' . $row['title'] . '">';
											$expenses_types = $Query->getN('expenses_types', ['fields' => 'id, title, cat_id', 'order' => ['title']]);
											if($expenses_types['status'] != 'empty'){ foreach ($expenses_types as $r) {
												if ($r['cat_id'] == $row['id']) {
													echo '<option value="' . $r['id'] . '">' . $r['title'] . '</option>';
												}
											} }
											echo '</optgroup>';
										} }
										?>
									</select>
									<div class="input-group-append" data-toggle="kt-tooltip" title="" data-placement="top" data-original-title="<?php echo $words['addcat']; ?>">
										<div class="btn btn-brand btn-upper" data-toggle="modal" data-target="#addExpenseCategoryModal"><i class="fas fa-plus text-white p-0"></i></div>
									</div>
									<div class="input-group-append" data-toggle="kt-tooltip" title="" data-placement="top" data-original-title="<?php echo $words['addtype']; ?>">
										<div class="btn btn-brand btn-upper" data-toggle="modal" data-target="#addExpenseTypeModal"><i class="fas fa-plus text-white p-0"></i></div>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group row d-none">
							<label class="col-4 col-form-label required"><?= $words['worker'] ?></label>
							<div class="col-8 workerContentForExpense"></div>
						</div>
						<div class="form-group row soni">
							<label class="col-4 col-form-label"><?= $words['soni'] ?></label>
							<div class="col-8">
								<input type="number" class="form-control expense_qty_input" name="qty">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-4 col-form-label required"><?= $words['price'] ?></label>
							<div class="col-8">
								<input type="text" class="form-control numberFormat expense_price_input text-left" name="price">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-4 col-form-label"><?= $words['amount'] ?></label>
							<div class="col-8">
								<input type="text" class="form-control numberFormat disabled expense_amount_input text-left" value="0" disabled>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-4 col-form-label"><?= $words['time'] ?></label>
							<div class="col-8">
								<input type="text" class="form-control datetimepicker w-100" name="time" value="<?= date("d.m.Y H:i", strtotime($now)) ?>" readonly />
							</div>
						</div>
						<div class="form-group row form-group-last">
							<label class="col-4 col-form-label"><?= $words['comment'] ?></label>
							<div class="col-8">
								<textarea class="form-control" name="comment"></textarea>
							</div>
						</div>
						<div class="text-right">
							<button class="btn btn-brand btn-upper btn-sm btn-bold mt-3"><?= $words['save'] ?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- Add Expenses Type Modal -->
	<div class="modal fade" id="addExpenseTypeModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content modal-sm">
				<div class="modal-header p-3">
					<h5 class="modal-title"><?= $words['harajatturi'] ?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="addExpenseTypeForm">
						<div class="form-group row">
							<label class="col-3 col-form-label"><?= $words['turi'] ?></label>
							<div class="col-9">
								<div class="input-group">
									<select name="cat_id" class="form-control kt_selectpicker" title="<?php echo $words['choose'] ?>">
										<?php
										if($expenses_cats['status'] != 'empty'){ foreach ($expenses_cats as $r) {
											echo '<option value="' . $r['id'] . '">' . $r['title'] . '</option>';
										} }
										?>
									</select>
									<div class="input-group-append" data-toggle="kt-tooltip" title="" data-placement="top" data-original-title="<?php echo $words['addcat']; ?>">
										<div class="btn btn-brand btn-upper" data-toggle="modal" data-dismiss="modal" aria-label="Close" data-target="#addExpenseCategoryModal"><i class="fas fa-plus text-white p-0"></i></div>
									</div>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-3 col-form-label"><?= $words['title'] ?></label>
							<div class="col-9">
								<input type="text" class="form-control" name="title">
							</div>
						</div>
						<div class="form-group-last row">
							<label class="col-3 col-form-label"><?php echo $words['active'] ?>:</label>
							<div class="col-9">
								<span class="kt-switch kt-switch--icon-check kt-switch--green">
									<label>
										<input type="checkbox" name="active">
										<span></span>
									</label>
								</span>
							</div>
						</div>
						<div class="text-right">
							<button class="btn btn-brand btn-upper btn-sm btn-bold mt-3"><?= $words['save'] ?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- Add Expenses Category Modal -->
	<div class="modal fade" id="addExpenseCategoryModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content modal-sm">
				<div class="modal-header p-3">
					<h5 class="modal-title"><?= $words['harajatbulimi'] ?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="addExpenseCategoryForm">
						<div class="form-group row">
							<label class="col-3 col-form-label"><?= $words['title'] ?></label>
							<div class="col-9">
								<input type="text" class="form-control" name="title">
							</div>
						</div>
						<div class="form-group-last row">
							<label class="col-3 col-form-label"><?php echo $words['important'] ?>:</label>
							<div class="col-9">
								<span class="kt-switch kt-switch--icon-check kt-switch--green">
									<label>
										<input type="checkbox" name="important">
										<span></span>
									</label>
								</span>
							</div>
						</div>
						<div class="form-group-last row">
							<label class="col-3 col-form-label"><?php echo $words['active'] ?>:</label>
							<div class="col-9">
								<span class="kt-switch kt-switch--icon-check kt-switch--green">
									<label>
										<input type="checkbox" name="active">
										<span></span>
									</label>
								</span>
							</div>
						</div>
						<div class="text-right">
							<button class="btn btn-brand btn-upper btn-sm btn-bold mt-3"><?= $words['save'] ?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<?php
	$roles = $Query->getN("roles", ['order' => ['title'.$_COOKIE['lang']]]);
	$usersList = $Query->getN("users", [
		'fields' => 'id, name, surname, role'
	]);

	foreach ($roles as $role) {
		foreach ($usersList as $key => $val) {
			if($role['value'] === 'teacher' || $role['value'] === 'pupil' || $role['value'] === 'admin' || $role['value'] === 'boss') continue;
			if($val['role'] != $role['id']) continue;
			$workers[$role['id']][$role['title'.$_COOKIE['lang']]][] = [
				'id' => $val['id'],
				'fio' => $val['surname']. ' ' .$val['name'],
			];
		}
	}
	$teachersJson = $Query->getN("teachers t", ['fields' => 'u.id, u.name, u.surname, s.title as subject_title', 'join' => [
		['table' => 'users u', 'on' => 't.user_id = u.id'],
		['table' => 'subjects s', 'on' => 't.subject_id = s.id'],
		]]);
	if($workers['status'] != 'empty'){?>
		<script>
			const workers = <?php echo json_encode($workers); ?>
		</script>
	<?php }else{ ?>
		<script>
			const workers = [{"id":"","fio":"<?php echo $words['notfoundteachers'];?>"}]
		</script>
	<?php }
	if($teachersJson['status'] != 'empty'){?>
		<script>
			const teachers = <?php echo json_encode($teachersJson); ?>
		</script>
	<?php }else{ ?>
		<script>
			const teachers = [{"id":"","name":"<?php echo $words['notfoundteachers'];?>"}]
		</script>
	<?php }?>
<?php endif; ?>

<?php if (($u_role['add_access'] === '1' || $u_role['value'] === 'teacher') && in_array('groups', $user_permissions)) : ?>
<!-- Attendance Modal -->
<div class="modal fade" id="addAttendanceModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content modal-md">
			<div class="modal-header p-3">
				<h5 class="modal-title"><?= $words['attendance'] ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="addAttendanceForm">

					<?php
					if(count(explode(',',$_SESSION['groups'])) > 1 || $u_role['is_admin'] === '1'){
						if($u_role['is_admin'] === '1'){
							$groupsForTeacher = $Query->getN("groups", ['order' => ['title']]);
						}elseif($u_role['value'] === 'teacher'){
							$groupsForTeacher = $Query->getN("groups", ["where" => [['value' => $_SESSION['groups'], 'column' => 'id', 'operand' => 'in']], 'order' => ['title']]);
						}
						?>
						<div class="form-group row">
							<label class="col-4 col-form-label"><?php echo $words['guruh'] ?></label>
							<div class="col-8">
								<select name="group_id" class="form-control kt_selectpicker changeGroupAttendance" title="<?= $words['choose'] ?>">
									<?php if($groupsForTeacher['status'] != 'empty') {
											foreach ($groupsForTeacher as $row) { ?>
											<option value="<?= $row['id'] ?>"><?= $row['title']?></option>
										<?php }
									 } ?>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-4"><?php echo $words['date'] ?></label>
							<div class="col-8">
								<input type="text" name="date" class="form-control datepicker w-100 changeGroupAttendance" value="<?php echo date("d.m.Y", strtotime($now)); ?>" readonly />
							</div>
						</div>
						<div id="attendanceGroupResult"></div>
						<div class="mt-4 text-right">
							<button class="btn btn-brand btn-upper" type="submit" disabled><?php echo $words['save'] ?></button>
						</div>
					<?php }elseif(count(explode(',',$_SESSION['groups'])) == 1 && $_SESSION['groups'] != '' && !empty($_SESSION['groups'])){ ?>
						<table class="table table-hover table-responsive-md" data-id="<?php echo $_SESSION['groups']; ?>">
							<thead>
								<tr>
									<th class="p-20px">№</th>
									<th align="left"><?php echo $words['fio'] ?></th>
									<th class="text-right">
										<span class="plusMinus kt-switch kt-switch--icon-check">
											<label>
												<input type="checkbox" class="plusMinusAll">
												<span></span>
											</label>
										</span>
									</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$usersForAttendance = $Query->getN('pupils p', ['fields' => 'p.id, p.user_id, CONCAT(u.surname, " ", u.name) as fio', 'join' => [
									['table' => 'users u', 'on' => 'p.user_id = u.id']],
									'where' => [['column' => 'p.group_id', 'value' => $_SESSION['groups']]],
									'order' => ['u.surname', 'u.name']]);
								if(count($usersForAttendance) > 0 && $usersForAttendance['status'] != 'empty'){
									foreach ($usersForAttendance as $row) { ?>
										<tr>
											<td><?php echo ++$numNotPupils ?></td>
											<td align="left"><a href="users?id=<?php echo $row['user_id'] ?>" target="_blank"><?php echo $row['fio']?></a>
											</td>
											<td align="right">
												<span class="plusMinus kt-switch kt-switch--icon-check">
													<label>
														<input type="hidden" name="pupil_id[]" value="<?php echo $row['id'];?>">
														<input type="checkbox" name="plus[]" value="<?php echo $row['id'];?>">
														<span></span>
													</label>
												</span>
											</td>
										</tr>
									<?php }
								} ?>
							</tbody>
						</table>
						<input type="hidden" name="group_id" value="<?php echo $_SESSION['groups'];?>">
						<div class="mt-4 d-flex justify-content-between align-items-center">
							<input type="text" name="date" class="form-control datepicker_top w-auto" value="<?php echo date("d.m.Y", strtotime($now)); ?>" readonly />
							<button class="btn btn-brand btn-upper" type="submit" disabled><?php echo $words['save'] ?></button>
						</div>
					<?php } ?>
				</form>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<?php if (in_array('messages', $user_permissions)): ?>
<!-- Send message Sticky Modal -->
<div class="modal modal-sticky-bottom-right modal-sticky-md mw-md-300px" id="sendMsgSticky" role="dialog" data-backdrop="false" aria-modal="true" style="padding-right: 3px; display: none;">
	<div class="modal-content kt-inbox">
		<form id="sendMsgStickyForm">
			<div class="kt-inbox__form">
				<div class="kt-inbox__head">
					<div class="kt-inbox__title"><?= $words['sendmsg'] ?></div>
					<div class="kt-inbox__actions">
						<button type="button" class="kt-inbox__icon kt-inbox__icon--sm" data-dismiss="modal">
							<i class="flaticon2-cross"></i>
						</button>
					</div>
				</div>

				<div class="kt-inbox__body p-4">
					<select name="to_id[]" class="form-control kt_selectpicker" multiple data-size="7" data-live-search="true" tabindex="-98" title="<?= $words['to'] ?>">
						<?php
						$usersWithoutUID = $Query->getN('users');

						if($usersWithoutUID['status'] !== 'empty') {
						foreach ($usersWithoutUID as $row) { ?>
							<option value="<?= $row['id'] ?>"><?= $row['surname'] . ' ' . $row['name'] ?></option>
						<?php } }?>
					</select>
					<input class="form-control mt-3" name="title" placeholder="<?= $words['title'] ?>:">
					<textarea name="text" class="form-control p-3 mt-3" rows="5" placeholder="<?= $words['matn'] ?>"></textarea>
				</div>
				<div class="kt-inbox__foot">
					<button type="submit" class="btn btn-brand btn-upper btn-bold">
						<?= $words['send'] ?>
					</button>
				</div>
			</div>
		</form>
	</div>
</div>
<?php endif; ?>

<?php if($u_role['is_admin'] === '1' || $u_role['value'] === 'teacher'): ?>
<!-- Add Announcement Modal -->
<div class="modal fade" id="announcementAddModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header p-3">
				<h5 class="modal-title"><?= $words['addpost'] ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="announcementAddForm">
					<?php if($u_role['is_admin'] === '1'): ?>
					<div class="form-group row">
						<label class="col-3 col-form-label required"><?= $words['turi'] ?></label>
						<div class="col-9">
							<select class="form-control kt_selectpicker" name="type" data-original-title="" title="<?php echo $words['choose'];?>">
								<option value="news"><?php echo $words['news'] ?></option>
								<option value="announcement"><?php echo $words['announcement'] ?></option>
							</select>
						</div>
					</div>
					<?php endif; ?>
					<div class="form-group row">
						<label class="col-3 col-form-label required"><?= $words['title'] ?></label>
						<div class="col-9">
							<input type="text" class="form-control" name="title">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-3 col-form-label required"><?= $words['description'] ?></label>
						<div class="col-9">
							<textarea type="text" class="form-control" name="description"></textarea>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-3 col-form-label required"><?= $words['text'] ?></label>
						<div class="col-9">
							<textarea type="text" class="form-control ck-editor" name="text"></textarea>
						</div>
					</div>
					<div class="text-right">
						<button class="btn btn-brand btn-upper btn-sm btn-bold mt-3"><?= $words['save'] ?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<!-- Event Add Modal -->
<div class="modal fade" id="eventAddModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content modal-sm">
			<div class="modal-header p-3">
				<h5 class="modal-title"><?php echo $words['add'] ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			</div>
			<form id="eventAddForm">
				<div class="modal-body py-10">
					<div class="form-group">
						<label class="fs-6 fw-semibold required mb-2"><?php echo $words['title'] ?></label>
						<input type="text" class="form-control form-control-solid" name="title" required>
					</div>
					<div class="row">
						<div class="col form-group">
							<label class="fs-4 fw-semibold mb-2 required"><?php echo $words['event_start_date'] ?></label>
							<input class="form-control datepicker w-100" name="start_event" type="text" readonly="readonly" required>
						</div>
						<div class="col form-group d-none" calendar="datepicker">
							<label class="fs-4 fw-semibold mb-2"><?php echo $words['event_start_time'] ?></label>
							<input class="form-control timepicker" name="start_event_time" type="text" readonly="readonly">
						</div>
					</div>
					<div class="row form-group">
						<div class="col">
							<label class="fs-4 fw-semibold mb-2 required"><?php echo $words['event_end_date'] ?></label>
							<input class="form-control datepicker w-100" name="end_event" type="text" readonly="readonly" required>
						</div>
						<div class="col d-none" calendar="datepicker">
							<label class="fs-4 fw-semibold mb-2"><?php echo $words['event_end_time'] ?></label>
							<input class="form-control timepicker" name="end_event_time" type="text" readonly="readonly">
						</div>
					</div>
					<div class="form-group">
						<label class="fs-6 fw-semibold mb-2"><?php echo $words['description'] ?></label>
						<textarea type="text" class="form-control form-control-solid" name="description"></textarea>
					</div>
					<input class="form-check-input" type="hidden" id="event_allday" name="allday" value="1">
					<?php if($noneed): ?>
					<div class="form-group">
						<label class="form-check form-check-custom form-check-solid">
							<input class="form-check-input event_allday" type="checkbox" id="event_allday" name="allday" checked>
							<span class="form-check-label fw-semibold" for="event_allday">All Day</span>
						</label>
					</div>
					<?php endif; ?>
					<div class="form-group-last">
						<label class="fs-4 fw-semibold mb-2"><?php echo $words['color'] ?></label>
						<div class="colors">
							<div class="colors-item">
								<label for="orange-color"></label>
								<input type="radio" name="color" id="orange-color" value="#fd7e14">
							</div>
							<div class="colors-item">
								<label for="yellow-color"></label>
								<input type="radio" name="color" id="yellow-color" value="#ffc107">
							</div>
							<div class="colors-item">
								<label for="warning-color"></label>
								<input type="radio" name="color" id="warning-color" value="#ffb822">
							</div>
							<div class="colors-item">
								<label for="red-color"></label>
								<input type="radio" name="color" id="red-color" value="#dc3545">
							</div>
							<div class="colors-item">
								<label for="light-green-color"></label>
								<input type="radio" name="color" id="light-green-color" value="#B4EB4C">
							</div>
							<div class="colors-item">
								<label for="dark-color"></label>
								<input type="radio" name="color" id="dark-color" value="#343a40">
							</div>
							<div class="colors-item">
								<label for="light-color"></label>
								<input type="radio" name="color" id="light-color" value="#f8f9fa">
							</div>
							<div class="colors-item">
								<label for="info-color"></label>
								<input type="radio" name="color" id="info-color" value="#5578eb">
							</div>
							<div class="colors-item">
								<label for="success-color"></label>
								<input type="radio" name="color" id="success-color" value="#1dc9b7">
							</div>
							<div class="colors-item">
								<label for="blue-color"></label>
								<input type="radio" name="color" id="blue-color" value="#007bff">
							</div>
							<div class="colors-item">
								<label for="indigo-color"></label>
								<input type="radio" name="color" id="indigo-color" value="#6610f2">
							</div>
							<div class="colors-item">
								<label for="purple-color"></label>
								<input type="radio" name="color" id="purple-color" value="#6f42c1">
							</div>
							<div class="colors-item">
								<label for="pink-color"></label>
								<input type="radio" name="color" id="pink-color" value="#e83e8c">
							</div>
							<div class="colors-item">
								<label for="green-color"></label>
								<input type="radio" name="color" id="green-color" value="#28a745">
							</div>
							<div class="colors-item">
								<label for="cyan-color"></label>
								<input type="radio" name="color" id="cyan-color" value="#17a2b8">
							</div>
							<div class="colors-item">
								<label for="white-color"></label>
								<input type="radio" name="color" id="white-color" value="#ffffff">
							</div>
							<div class="colors-item">
								<label for="gray-color"></label>
								<input type="radio" name="color" id="gray-color" value="#6c757d">
							</div>
							<div class="colors-item">
								<label for="primary-color"></label>
								<input type="radio" name="color" id="primary-color" value="#5867dd">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer flex-center">
					<button type="submit" class="btn btn-brand btn-upper btn-sm">
						<span class="indicator-label"><?php echo $words['save'] ?></span>
						<span class="indicator-progress d-none"><?php echo $words['saving'] ?>... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<?php unset($bottomConfig);

//=================================== Modal windows END ====================================

include_once 'mainscripts.php';
