<?php
    $perPageOptions = [10, 20, 50, 75, 100, 150, 200];
?>

<?= $this->extend('v2/layout/main_layout') ?>

<?= $this->section('css') ?>
<style>
    .employee-edit form {
        .card-header {
            font-weight: bold;
        }
        .card-body {
            ul {
                padding: 0;
            }
        }
        .user-login-info {
            .card-body {
                font-size: 80%;
                input, select {
                    font-size: 90%;
                }
            }
        }
        &:not(.need-password) {
            #password-section {
                display: none;
            }
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="p-4">
    <input type="hidden" name="offset" id="offset" value="<?= $offset ?>" />

    <h5 class="mt-2">Common List of Users</h5>

    <div class="order-table-header d-flex">
        <div class="flex-fill d-flex align-items-center">
            <div class="d-flex align-items-center whitespace-nowrap">
                <?php if($total_rows > 0) { ?>
                    <span class="mx-2 text-black"><?= $from ?> - <?= $to ?></span>
                    <label>of</label>
                    <span class="mx-2 text-black"><?= $total_rows ?></span>
                    <label>employees</label>
                <?php } else { ?>
                    No found Employees
                <?php } ?>
            </div>

            <div class="d-flex align-items-center ms-2 ms-sm-4">
                <label class="me-2 d-none d-sm-block">Display</label>
                <select class="form-select" name='per_page' id="per_page" aria-label="Display Per Page">
                    <?php foreach($perPageOptions as $perPage) { ?>
                        <option value='<?= $perPage ?>' <?= ($per_page == $perPage) ? "selected='true'" : "" ?>><?=$perPage?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="d-flex">
            <?= view('v2/components/SearchInput2', ['name'=>'search', 'id'=>'search', 'value'=>$search ?? '', 'placeholder' => 'Search here']) ?>
            <button class="btn btn-primary m-0 ms-4 px-3" id="add_user">New User</button>
        </div>
    </div>

    <div class="mt-3" style="overflow: auto;">
        <table class="table order-primary-table">
            <thead>
                <tr>
                    <th scope="col">Account No</th>
                    <th scope="col">Email</th>
                    <th scope="col">Order Types</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if($total_rows > 0) {
                    foreach($employees as $employee) { 
                ?>
                    <tr 
                        data-person-id="<?= $employee->person_id ?>"
                        data-username="<?= $employee->username ?>"
                        data-username="<?= $employee->email ?>"
                    >
                        <td><?= $employee->username ?></td>
                        <td><?= $employee->email ?></td>
                        <td><div class="d-flex">
                            <?php if($employee->delivery == '1') { ?>
                                <span>Deliver (Delivery Charte: £ <?= $employee->delivery_charge ?? 0 ?>)</span>
                            <?php } ?>
                            <?php if($employee->collect == '1') { ?>
                                <span class="mx-1 <?= $employee->delivery != '1' ? 'd-none' : '' ?>">|</span>
                                <span>Collect</span>
                            <?php } ?>
                            <?php if($employee->pay == '1') { ?>
                                <span class="mx-1 <?= ($employee->delivery != '1' && $employee->pay != '1') ? 'd-none' : '' ?>">|</span>
                                <span>Pay</span>
                            <?php } ?>
                        </div></td>
                        <td>
                            <i class="bi bi-pencil-square employee-edit cursor-pointer" style="font-size:20px; color: #ff6c00;"></i>
                        </td>
                    </tr>
                <?php }
                } else { ?>
                    <tr>
                        <td colspan="4" class="text-center">No found Employees</td>
                    </tr>
                <?php 
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php if($total_rows > 0) { ?>
        <div class="d-flex justify-content-end mt-4">
            <?= view('v2/components/Pagination', [
                'curd_page'=>$curd_page, 
                'total_page'=>$total_page,
                'base_url' => $base_url,
                'per_page' => $per_page,
            ]) ?>
        </div>
    <?php } ?>


</section>
<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script>
$(document).ready(function(e) {
    $(document).on('change', 'input#search', function(e) {
        const url = `/employees?search=${e.target.value}`;
        window.location.href = url;
    })

    $(document).on('click', 'button#add_user', function(e) {
        e.preventDefault();
        e.stopPropagation();

        $view_modal = $(".view-modal");
        $view_modal.removeClass('modal-sm modal-md modal-lg modal-xl');
        add_loadingSpinner_to_button(e.target);

        $.ajax({
            url: `/employees/create`,
            method: 'GET',
            error: function (request, status, error) {

            },
            success: function(response) {
                $view_modal.addClass('modal-lg');
                $view_modal.find(".modal-header .modal-title").html('Create User');
                $view_modal.find(".modal-content .modal-body").html(response);
                const modal = new bootstrap.Modal($view_modal[0]);
                modal.show();

                remove_loadingSpinner_from_button(e.target);
            }
        })        
    })

    $(document).on('click', 'table tbody tr i.employee-edit', function(e) {
        e.preventDefault();
        e.stopPropagation();

        $tr = $(e.target).closest('tr');
        
        const person_id = $tr.data('person-id');
        const username = $tr.data('username');
        
        $view_modal = $(".view-modal");
        $view_modal.removeClass('modal-sm modal-md modal-lg modal-xl');

        $(e.target).addClass("rotate-spinner bi-arrow-repeat");
        $(e.target).removeClass("bi-pencil-square employee-edit cursor-pointer");

        $.ajax({
            url: `/employees/edit/${person_id}`,
            method: 'GET',
            error: function (request, status, error) {

            },
            success: function(response) {
                $view_modal.addClass('modal-lg');
                $view_modal.find(".modal-header .modal-title").html('Edit User');
                $view_modal.find(".modal-content .modal-body").html(response);
                const modal = new bootstrap.Modal($view_modal[0]);
                modal.show();

                $(e.target).removeClass("rotate-spinner bi-arrow-repeat");
                $(e.target).addClass("bi-pencil-square employee-edit cursor-pointer");
            }
        })
    })

    $(document).on('click', '.employee-edit button#btn_generate_key', function(e) {
        const $form = $(".employee-edit form");
        const person_id = $form.find("input#person_id").val();

        add_loadingSpinner_to_button(e.target);

        $.ajax({
            url: `/employees/generate_key/${person_id}`,
            method: 'get',
            success: function(response) {
                $form.find("input#api_key").val(response);
                remove_loadingSpinner_from_button(e.target);
            }
        })
    })

    $(document).on('click', '.employee-edit form input#api_key', function(e) {
        e.preventDefault();
        e.stopPropagation();

        if(!$(e.target).val()) return;

        $(e.target).select();
        setTimeout(function() {
            document.execCommand("copy");
            showToast({
                type: 'success',
                message: "API Key was copied to the clipboard",
            });
        }, 500)
    })

    $(document).on('click', '.employee-edit button#btn_copy_key', function(e) {
        $(".employee-edit form input#api_key").trigger('click');
    })

    $(document).on('change', '.employee-edit form #change_password', function(e) {
        const $form = $(".employee-edit form");
        if(e.target.checked) {
            $form.addClass('need-password');
            $form.find("#password").attr('required', true);
            $form.find("#repeat_password").attr('required', true);
        } else {
            $form.removeClass('need-password');
            $form.find("#password").attr('required', false);
            $form.find("#repeat_password").attr('required', false);
        }
    })

    $(document).on('submit', '.employee-edit form', function(e) {
      debugger
        const $form = $(e.target);
        const person_id = $form.find("#person_id").val();
        const password = $form.find("#password").val();
        const repeat_password = $form.find("#repeat_password").val();
        const $btnSave = $form.find('button#btn_save');

        // branches
        let branches = [];
        $branch_chkboxes = $form.find("input.branch:checked");
        for(let i=0; i<$branch_chkboxes.length; i++) {
            branches.push($branch_chkboxes[i].value);
        }
        $form.find("input#branches").val(branches);

        if(person_id) {         // update account
            if($form.find("#change_password")[0].checked) {
                if(password != repeat_password) {
                    showToast({
                        type: 'error',
                        message: 'Incorrect password'
                    });
                    $btnSave.find("i").remove();
                    return false;
                }
            }
        } else {               // new account
            if(password != repeat_password) {
                showToast({
                    type: 'error',
                    message: 'Incorrect password'
                })
                $btnSave.find("i").remove();
                return false;
            }

            if($form.find("#username_email_available").val() != "1") {
                // To check whether the same username & email exists.
                $.ajax({
                    url: '/employees/check_exist',
                    method: 'get',
                    data: {
                        username: $form.find("#username").val(),
                        email: $form.find("#email").val(),
                    },
                    success: function(response) {
                        if(response.success == 0) {
                            showToast({
                                type: 'error',
                                message: response.msg
                            })

                            remove_loadingSpinner_from_button($form.find("button[type='submit']"));
                        } else {
                            $form.find("#username_email_available").val(1);
                            $form.trigger('submit');
                        }
                    }
                })

                return false;
            }
        }

        return true;
    })
})
</script>
<?= $this->endSection() ?>