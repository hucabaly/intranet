jQuery(document).ready(function ($) {
    /**
     * update check/uncheck function unit
     * @param {type} dom
     * @returns {undefined}
     */
    function updateCheckFunction(dom)
    {
        var dataId = dom.data('id');
        if (dom.is(':checked')) {
            $('.team-group-function[data-id=' + dataId + ']').show();
        } else {
            $('.team-group-function[data-id=' + dataId + ']').hide();
        }
    }
    //update checkbox is-function
    $('input.input-is-function').each( function( i, v ) {
        updateCheckFunction($(this));
    });
    $('input.input-is-function').on('change', function (event) {
        updateCheckFunction($(this));
    });
    
    /**
     * add / remove team action
     */
    htmlAddTeamPositonOrigin = $('.group-team-position-orgin').html();
    $('.group-team-position-orgin').remove();
    dataIdLast = jQuery('.box-form-team-position').children('.group-team-position').length;
    if (!dataIdLast) {
        dataIdLast = 0;
    } else {
        dataIdLast = parseInt(dataIdLast);
    }
    if (dataIdLast == 1) {
        $('.box-form-team-position .group-team-position .input-remove').addClass('warning-action');
    }
    $(document).on('click', '.input-team-position.input-add-new button', function(event) {
        dataIdLast++;
        htmlAddTeamPositon = $(htmlAddTeamPositonOrigin);
        htmlAddTeamPositon.find('.input-team-position.input-team select').attr('name', 'team[' + dataIdLast + '][team]');
        htmlAddTeamPositon.find('.input-team-position.input-position select').attr('name', 'team[' + dataIdLast + '][position]');
        $('.box-form-team-position').append(htmlAddTeamPositon);
        $('.box-form-team-position .group-team-position .input-remove').removeClass('warning-action');
        
        if ($('.box-form-team-position').children('.group-team-position').length == 1) {
            $('.box-form-team-position .group-team-position .input-remove').addClass('warning-action');
        }
        $('input[name=employee_team_change]').val(1);
    });
    $(document).on('click', '.input-team-position.input-remove', function(event) {
        teamLength = $('.box-form-team-position').children('.group-team-position').length;
        if (teamLength > 1) {
            $(this).parents('.group-team-position').remove();
            $('input[name=employee_team_change]').val(1);
        }
        if (teamLength == 2) {
            $('.box-form-team-position .group-team-position .input-remove').addClass('warning-action');
        }
    });
    //change select, update team availabel
    $(document).on('change', '.group-team-position select', function(e) {
        $('input[name=employee_team_change]').val(1);
    });
    
    /**
     * update role label current
     */
    $('#employee-role-form').on('hide.bs.modal', function (e) {
        htmlRoleList = '';
        $(this).find('.checkbox input:checked').each(function (i,k) {
            htmlRoleList += '<li><span>';
            htmlRoleList += $(this).parent().text().trim();
            htmlRoleList += '</li></span>';
        });
        $('ul.employee-roles').html(htmlRoleList);
        $('input[name=employee_role_change]').val(1);
    });
    
    /**
     * padding table rule
     */
    lengthTrTableRule = $('.team-rule-wrapper table.table-team-rule tbody tr').length;
    $('.team-rule-wrapper table.table-team-rule tbody tr:last-child .form-input-dropdown').addClass('input-rule-last');
    $('.team-rule-wrapper table.table-team-rule tbody tr:nth-child(' + (lengthTrTableRule - 1) + ') .form-input-dropdown').addClass('input-rule-last');
    var paddingTableRule = $('.team-rule-wrapper .table-responsive').css('padding-bottom');
    $('.input-rule-last').on('show.bs.dropdown', function () {
        $('.team-rule-wrapper .table-responsive').css('padding-bottom', '90px');
    });
    $('.input-rule-last').on('hide.bs.dropdown', function () {
        $('.team-rule-wrapper .table-responsive').css('padding-bottom', paddingTableRule);
    });
});

/**
 * team member skill
 */
(function($){
    $.fn.employeeSkillAction = function(option) {
        
        //align center for image
        $('.employee-skill-modal').on('shown.bs.modal', function (e) {
            idModal = $(this).attr('id');
            if (idModal) {
                idModal = '#' + idModal + ' ';
            } else {
                idModal = '';
            }
            $(this).find('.input-box-img-preview .skill-modal-image').verticalCenter({
                parent: idModal + '.input-box-img-preview .skill-modal-image-preview'
            });
        });
        
        var autoComplete = {},
            groupChange = {
                schools: 0
            },
            dataGroup,
            dataHrefModal,
            dataItemId,
            imagePreviewImageDefault,
            employeeSkillNo = {},
            tokenValue,
            employeeSkill,
            messageError,
            labelFormat,
            urlLoadAutoComplete;
        
        //init var
        tokenValue = $('input[name=_token]').val();
        autoComplete = option.autoComplete;
        imagePreviewImageDefault = option.imagePreviewImageDefault;
        employeeSkillNo = option.employeeSkillNo;
        employeeSkill = option.employeeSkill;
        messageError = option.messageError;
        labelFormat = option.labelFormat;
        urlLoadAutoComplete = option.urlLoadAutoComplete,
        groupChange = option.groupChange;

        //click button to show modal
        $(document).on('click', '.employee-skill-box-wrapper [data-modal=true]', function(event) {
            dataItemId = $(this).parents('.esbw-item').data('id');
            if (!dataItemId || dataItemId == undefined) {
                dataItemId = 0;
            }
            dataGroup = $(this).parents('.employee-skill-box-wrapper').data('group');
            dataHrefModal = $(this).parents('.employee-skill-box-wrapper').data('href');
            dataIsChange = $(this).parents('.employee-skill-box-wrapper').data('change');
            $(dataHrefModal).modal('show');
            if (! dataItemId) {
                $(dataHrefModal).find('.btn-delete.btn-action').addClass('hidden');
            } else {
                $(dataHrefModal).find('.btn-delete.btn-action').removeClass('hidden');
            }
            //process data when show modal
            $(dataHrefModal).on('shown.bs.modal', function (e) {
                $(this).find('.input-skill-modal').removeAttr('disabled').val('');
                $(this).find('img.college-image-preview').attr('src', imagePreviewImageDefault);
                $(this).find('.input-skill-modal').each(function (i,k) {
                    inputType = $(this).attr('type');
                    dataCol = $(this).data('col');
                    dataTbl = $(this).data('tbl');
                    if (! dataCol || ! dataTbl) {
                    } else {
                        valueId = employeeSkill[dataGroup][dataItemId][dataTbl].id;
                        value = employeeSkill[dataGroup][dataItemId][dataTbl][dataCol];
                        if (inputType == 'file') {
                            if ($(this).parents('.input-box-img-preview').length) {
                                $(this).parents('.input-box-img-preview')
                                    .find('img[data-tbl=' + dataTbl +'][data-col=' + dataCol +'_preview]')
                                    .attr('src', value);
                            }
                            if (valueId) {
                                if ($(this).hasClass('not-auto')) {
                                    $(this).val('');
                                } else {
                                    $(this).attr('disabled', true).val('');
                                }
                            }
                        } else {
                            if (value && value != undefined) {
                                value = $.parseHTML(value)[0].nodeValue;
                            } else {
                                value = '';
                            }
                            if (
                                valueId && 
                                ! $(this).data('autocomplete') && 
                                $(this).attr('type') != 'hidden'
                            ) {
                                if ($(this).hasClass('not-auto')) {
                                    $(this).val(value);
                                } else {
                                    $(this).attr('disabled', true).val(value);
                                }
                            } else {
                                $(this).val(value);
                            }
                            if ($(this).is('select')) {
                                $(this).val(value).trigger("change");
                            }
                        }
                    }
                });
                $(this).attr('data-id', dataItemId);
            });
        });
        
        /**
        * update employee skill data
         */
        function updateEmployeeData(thisDomModal, imageReturnAjax, actionButton) {
            if (! imageReturnAjax || imageReturnAjax == undefined) {
                imageReturnAjax = null;
            }
            var id = thisDomModal.attr('data-id');
            id = parseInt(id);
            var group = thisDomModal.data('group');
            groupChange[group] = 1;
            // action delete skill
            if (actionButton && actionButton == 'delete') {
                delete employeeSkill[group][id];
                $('input[name=employee_skill]').val($.param(employeeSkill));
                $('input[name=employee_skill_change]').val($.param(groupChange));
                return true;
            }
            
            //action edit/add skill
            if (! id) {
                id = employeeSkillNo[group];
                employeeSkillNo[group]++;
            }
            
            //check same school
            inputIdCheck = thisDomModal.find('input[data-col=id]');
            flagCheckInputSame = false;
            if (inputIdCheck.length && inputIdCheck.val()) {
                dataTbl = inputIdCheck.data('tbl');
                $.each(employeeSkill[group], function (i,k) {
                    if (id == i) {
                        return true;
                    }
                    if (k[dataTbl].id == inputIdCheck.val()) {
                        flagCheckInputSame = true;
                        return false;
                    }
                });
            }
            if (flagCheckInputSame) {
                alert(messageError["same_" + group]);
                return true;
            }
            
            thisDomModal.find('.input-skill-modal[data-tbl][data-col]').each(function (i,k) {
                inputType = $(this).attr('type');
                dataCol = $(this).data('col');
                dataTbl = $(this).data('tbl');
                if (! dataCol || ! dataTbl) {
                } else {
                    valueInput = $(this).val();
                    if (employeeSkill[group][id] == undefined) {
                        employeeSkill[group][id] = {};
                    }
                    if (employeeSkill[group][id][dataTbl] == undefined) {
                        employeeSkill[group][id][dataTbl] = {};
                    }
                    if (employeeSkill[group][id][dataTbl][dataCol] == undefined) {
                        employeeSkill[group][id][dataTbl][dataCol] = {};
                    }
                    
                    if (inputType == 'file') {
                        if ($(this).parents('.input-box-img-preview').length) {
                            if (imageReturnAjax && imageReturnAjax.image && imageReturnAjax.image_path) {
                                valueInput = imageReturnAjax.image;
                                employeeSkill[group][id][dataTbl].image_path = imageReturnAjax.image_path;
                            } else {
                                valueInput = $(this).parents('.input-box-img-preview')
                                    .find('img[data-tbl=' + dataTbl +'][data-col=' + dataCol +'_preview]')
                                    .attr('src');
                                employeeSkill[group][id][dataTbl].image_path = '';
                            }
                        }
                    }
                    employeeSkill[group][id][dataTbl][dataCol] = valueInput;
                }
            });
            $('input[name=employee_skill]').val($.param(employeeSkill));
            $('input[name=employee_skill_change]').val($.param(groupChange));
        }
        
        /**
        * upload skill html list
         */
        function updateHtmlSkillList(group)
        {
            skillListWrapper = $('.employee-skill-box-wrapper[data-group=' + group + '] .employee-skill-items');
            skillListItem0 = skillListWrapper.find('.esbw-item[data-id=0]');
            var htmlNew = '';
            $.each(employeeSkill[group], function(i, k){
                if (i == 0) {
                    return true;
                }
                skillItemNew = skillListItem0.clone();
                skillItemNew.attr('data-id', i);
                skillItemNew.removeClass('hidden');
                skillItemNew.find('[data-tbl][data-col]').each(function(){
                    dataTbl = $(this).data('tbl');
                    dataCol = $(this).data('col');
                    if ($(this).is('img')) {                        
                        skillItemNew.find('[data-tbl=' + dataTbl + '][data-col=' + dataCol + ']').attr('src', k[dataTbl][dataCol]);
                    } else {
                        valueInput = k[dataTbl][dataCol];
                        domInput = skillItemNew.find('[data-tbl=' + dataTbl + '][data-col=' + dataCol + ']');
                        dataDateFormat = domInput.data('date-format');
                        dataLabelFormat = domInput.data('label-format');
                        if (dataDateFormat) {
                            date = new Date(valueInput);
                            valueInput = getDateFormat(date, dataDateFormat);
                        } else if (dataLabelFormat) {
                            valueInput = labelFormat[dataLabelFormat][valueInput];
                        }
                        domInput.html(valueInput);
                    }
                });
                htmlNew += skillItemNew[0].outerHTML;
            });
            skillListWrapper.children(':not(.esbw-item)').html(htmlNew);
        }
        
        //process data when modal action change
        $('.employee-skill-modal .btn-action.btn-add, .employee-skill-modal .btn-action.btn-edit').on('click', function(event) {
            var thisDomModal = $(this).parents('.employee-skill-modal');
            var dataGroupType = thisDomModal.data('group');
            formDomModal = thisDomModal.find('form');
            if (formDomModal.length) {
                if (! formDomModal.valid()) {
                    return true;
                }
            }
            //upload if available
            if (thisDomModal.find('input[type=file]').val()) {
                formDom = thisDomModal.find('form.skill-modal-form');
                formData = new FormData();
                formData.append('_token', tokenValue);
                formData.append('skill_type', dataGroupType);
                formData.append('file', formDom.find('input[type=file]')[0].files[0]);
                $.ajax({
                    url : formDom.attr('action'),
                    type : 'POST',
                    data : formData,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success : function(data) {
                        if (data.error != undefined && data.error) {
                            alert(data.error);
                        } else {
                            updateEmployeeData(thisDomModal, data);
                            updateHtmlSkillList(dataGroupType);
                        }
                        thisDomModal.modal('hide');
                    }
                });
            } else {
                updateEmployeeData(thisDomModal);
                updateHtmlSkillList(dataGroupType);
                thisDomModal.modal('hide');
            }
        });
        //process data delete
        $('.employee-skill-modal .btn-action.btn-delete').on('click', function(event) {
            thisDomModal = $(this).parents('.employee-skill-modal');
            dataGroupType = thisDomModal.data('group');
            dataId = thisDomModal.attr('data-id');
            dataId = parseInt(dataId);
            if (dataId != 0) {
                updateEmployeeData(thisDomModal, null, 'delete');
                updateHtmlSkillList(dataGroupType);
            }
            thisDomModal.modal('hide');
        });
        
        // remove pop up in modal when scroll
        $('.employee-skill-modal').scroll(function(){
            $('input').blur();
        });
        
        //disable submti fomr modal
        $('.employee-skill-modal form').on('submit', function(event) {
            event.preventDefault();
        });
        
        //process data when close modal
        $('.employee-skill-modal').on('hidden.bs.modal', function(e) {
            $(this).find('input').removeAttr('disabled').val('');
            $(this).find('img[data-col=image_preview]').attr('src', imagePreviewImageDefault);
        });
        
        /**
         * init autocomplete field
         */
        function initAutocompleteModal() {
            //autocomplete field in modal
            $('.employee-skill-modal input[data-autocomplete=true]').each(function() {
                var dataTblAuto = $(this).data('tbl');
                if (autoComplete[dataTblAuto] == undefined || 
                    ! autoComplete[dataTblAuto]) {
                    return true;
                }
                autoComplete[dataTblAuto] = JSON.parse(autoComplete[dataTblAuto]);
                $(this).autocomplete({
                    minLength: 0,
                    source: autoComplete[dataTblAuto],
                    select: function( event, ui ) {
                        thisParent = $(this).parents('.employee-skill-modal');
                        var uiItemSelected = ui.item;
                        thisParent.find('.input-skill-modal[data-tbl=' + dataTblAuto + ']:not([data-autocomplete=true])').each(function (){
                            if ($(this).hasClass('not-auto')) {
                                return true;
                            }
                            inputType = $(this).attr('type');
                            dataCol = $(this).data('col');
                            if (! dataCol) {
                            } else {
                                value = uiItemSelected[dataCol];
                                if (inputType == 'file') {
                                    if ($(this).parents('.input-box-img-preview').length) {
                                        $(this).parents('.input-box-img-preview')
                                            .find('img[data-col=' + dataCol +'_preview]')
                                            .attr('src', value);
                                    }
                                    $(this).attr('disabled', true).val('');
                                } else {
                                    if (value && value != undefined) {
                                        value = $.parseHTML(value)[0].nodeValue;
                                        $(this).attr('disabled', true).val(value);
                                    } else {
                                        $(this).attr('disabled', true).val('');
                                    }
                                }
                            }
                        });
                    }
                }).focus(function(){
                    $(this).autocomplete("search");
                });
            });
        }
        
        /**
         * load autocomplete data throw ajax
         */
        function loadAjaxAutocomplete() {
            $.ajax({
                url: urlLoadAutoComplete,
                type: 'POST',
                data: {'_token': tokenValue},
                dataType: 'json',
                success: function(dataReturn) {
                    autoComplete = dataReturn;
                    initAutocompleteModal();
                }
            });
        }
        $(window).load(function() {
            loadAjaxAutocomplete();
        });
        
        //process data when key press autocomplete field
        $('.employee-skill-modal input[data-autocomplete=true]').on('keyup', function(e) {
            if (e.keyCode == 13) {
            } else {
                thisParent = $(this).parents('.employee-skill-modal');
                var dataTblAuto = $(this).data('tbl');
                thisParent.find('input[data-tbl=' + dataTblAuto + ']:not([data-autocomplete=true])').each(function (){
                    inputType = $(this).attr('type');
                    if (inputType == 'file') {
                        $(this).removeAttr('disabled');
                        return true;
                    } else {
                        if ($(this).hasClass('not-auto')) {
                            $(this).removeAttr('disabled')
                            return true;
                        }
                        if ($(this).data('col') == 'id') {
                            $(this).removeAttr('disabled').val('');
                            return true;
                        }
                        $(this).removeAttr('disabled');
                        return true;
                    }
                });
            }
        });
    };
})(jQuery);
//# sourceMappingURL=script.js.map
