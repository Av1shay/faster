jQuery(document).ready(function ($) {
    var visibilityOpsRadio = $('input[type=radio][name=faster_post_visibility]');
    var visibilityRolesSelector = $('#visibility-roles');
    var rolesSelectCount = $('#visibility-roles :selected').length;
    var isDefinedRolesChecked = $('#visibility-define-roles').is(':checked');

    // Don't let user submit the form if he didn't chose any role
    $('#post').submit(function (e) {
        if ( isDefinedRolesChecked && rolesSelectCount === 0 ) {
            e.preventDefault();
            alert("You must chose at least one role that can see this post!");
            return false;
        }

        return true;
    });

    if ( isDefinedRolesChecked ) {
        visibilityRolesSelector.removeClass('hide');
    }

    visibilityOpsRadio.change(function () {
        isDefinedRolesChecked = $('#visibility-define-roles').is(':checked');
        if ( this.value === 'visibility_define_roles' ) {
            visibilityRolesSelector.removeClass('hide');
        } else {
            visibilityRolesSelector.addClass('hide');
        }
    });

    visibilityRolesSelector.change(function () {
        rolesSelectCount = $('#visibility-roles :selected').length;
    });

});