var personalHistory = '';
var oldValue = '';
$(document).ready(function () {
    $('input[type="number"]').attr("min", "0");
    utType($('.ut-type').val());

    $(document).on('change','.next-date',function(e){
        var dateValue = $(this).val();
        var day = null;
        var medicine_days = dateDiffernce(dateValue);
        $('.till-follow-up').val(medicine_days);
        getNextAppointmentDate(null,day,dateValue,null);
        
    });
    $(document).on('change', 'select.plan-follow-up', function (e) {
        var plan = $(this).val().toString();
        var daysToAdd = 0;
        var daysToAdd1 = 0;
        var daysToAdd2 = 0;
        var inducing_date = '';
        var inducing_date1 = '';
        var inducing_date2 = '';
        var inducing_date3 = '';
        $('.second-visit-inducing').multiDatesPicker('value','');
        $('.second-visit-inducing').multiDatesPicker({
            minDate: 0
        });
        if (plan.includes('2 ')) {
            daysToAdd = 1;
        }
        if (plan.includes('6 ')) {
            daysToAdd = 5;
        }
        if (plan.includes('3/5/7 day')) {
            daysToAdd1 = 3;
            daysToAdd2 = 5;
            daysToAdd = 7;
        }
        // if (plan.includes('100') || plan.includes(' 5 ')) {
        //     daysToAdd = 9;
        // }
        $("input[name='data[plan][follow_up]']").val('');
        $('.second-visit-inducing').val('');
        if (daysToAdd != 0) {
            var addedLmpDate =  new Date($(".second-visit-lmd-date").val());
            var lmd_date_diff = $('.lmd-date-diff-val').val();
            var follow_up_date = '';
            if(daysToAdd1 != 0 && daysToAdd2 != 0)
            {
                inducing_date = addedLmpDate.setDate(addedLmpDate.getDate() + (daysToAdd1-1));
                inducing_date1 = moment(inducing_date).format('MM/D/Y');
                inducing_date = addedLmpDate.setDate(addedLmpDate.getDate() + 2);
                inducing_date2 = moment(inducing_date).format('MM/D/Y');
                inducing_date = addedLmpDate.setDate(addedLmpDate.getDate() + 2);
                inducing_date3 = moment(inducing_date).format('MM/D/Y');
            }
            addedLmpDate.setDate(addedLmpDate.getDate() + daysToAdd);
            addedLmpDate = moment(addedLmpDate).format('dddd DD MMMM YYYY');
            inducing_date = moment(addedLmpDate).format('MM/D/Y');
            if(addedLmpDate != 'Invalid date'){
                if(inducing_date1 != '' && inducing_date2 != '' && inducing_date3 != '')
                {
                    $('.second-visit-inducing').multiDatesPicker('value',inducing_date1+', '+inducing_date2+', '+inducing_date3);
                    $('.second-visit-inducing').val(inducing_date1+', '+inducing_date2+', '+inducing_date3);
                    follow_up_date = new Date(inducing_date3);
                }
                else
                {
                    $('.second-visit-inducing').multiDatesPicker('value',inducing_date);
                    $('.second-visit-inducing').val(inducing_date);
                    follow_up_date = new Date(inducing_date);
                }
                // $("input[name='data[plan][follow_up]']").val(addedLmpDate);
                follow_up_date.setDate(follow_up_date.getDate() + 1);
                follow_up_date = moment(follow_up_date).format('dddd DD MMMM YYYY');
                $("input[name='data[plan][follow_up]']").val(follow_up_date);
                $(".follow-up-date").val(follow_up_date);
            }
        }
    });

    $(document).on('change', 'select.oe_ovary_left_details', function (e) {
        var textboxName = 'data[ovary][left][updated_details][]';
        if($(this).data('type') == 'oe')
        {
            var textboxName = 'oe[ovary][left][updated_details][]';
        }
        if (typeof ($(this).data('id')) !== 'undefined') {
            var textboxName = 'data[oe][ovary][left][updated_details][]';
        }
        var selectedValues = $('#oe_ovary_left_details').val();
        var updatedDetails = $('.edited_oe_ovary_left_details').map(function () {
            return this.id;
        }).get();
        var difference = [];
        var elementDifference = [];
        jQuery.grep(selectedValues, function (element) {
            if (jQuery.inArray(element.replace(/[!@#$&()\\`.+,/\"%\-*{}[|:;'<>~?^_=\] ]/g, '_'), updatedDetails) == -1) {
                elementDifference.push(element);
                difference.push(element.replace(/[!@#$&()\\`.+,/\"%\-*{}[|:;'<>~?^_=\] ]/g, '_'));
            }
        });
        for (var i = 0; i < selectedValues.length; i++) {
            selectedValues[i] = selectedValues[i].replace(/[!@#$&()\\`.+,/\"%\-*{}[|:;'<>~?^_=\] ]/g, '_');
        }

        var remove = [];
        jQuery.grep(updatedDetails, function (el) {
            if (jQuery.inArray(el, selectedValues) == -1) {
                remove.push(el);
            }
        });
        if (selectedValues.length > updatedDetails.length) {
            for (var i = 0; i < difference.length; i++) {
                $('.edit_oe_ovary_left_details').append(
                    '<div class="form-group col-md-12" id="' + difference[i] + '_left">' +
                    '<input class="form-control edited_oe_ovary_left_details" name="' + textboxName + '" type="text" value="' + elementDifference[i] + '" id="' + difference[i] + '" maxlength="250" required>' +
                    '</div>'
                );
            }
        }
        if (updatedDetails.length > $('#oe_ovary_left_details').val().length) {
            $('#' + remove + '_left').remove();
        }
    });

    $(document).on('change', 'select.oe_ovary_right_details', function (e) {
        var textboxName = 'data[ovary][right][updated_details][]';
        if($(this).data('type') == 'oe')
        {
            var textboxName = 'oe[ovary][right][updated_details][]';
        }
        if (typeof ($(this).data('id')) !== 'undefined') {
            var textboxName = 'data[oe][ovary][right][updated_details][]';
        }
        var selectedValues = $('#oe_ovary_right_details').val();
        var updatedDetails = $('.edited_oe_ovary_right_details').map(function () {
            return this.id;
        }).get();
        var difference = [];
        var elementDifference = [];
        jQuery.grep(selectedValues, function (element) {
            if (jQuery.inArray(element.replace(/[!@#$&()\\`.+,/\"%\-*{}[|:;'<>~?^_=\] ]/g, '_'), updatedDetails) == -1) {
                elementDifference.push(element);
                difference.push(element.replace(/[!@#$&()\\`.+,/\"%\-*{}[|:;'<>~?^_=\] ]/g, '_'));
            }
        });
        for (var i = 0; i < selectedValues.length; i++) {
            selectedValues[i] = selectedValues[i].replace(/[!@#$&()\\`.+,/\"%\-*{}[|:;'<>~?^_=\] ]/g, '_');
        }
        var remove = [];
        jQuery.grep(updatedDetails, function (el) {
            if (jQuery.inArray(el, selectedValues) == -1) remove.push(el);
        });
        if (selectedValues.length > updatedDetails.length) {
            for (var i = 0; i < difference.length; i++) {
                $('.edit_oe_ovary_right_details').append(
                    '<div class="form-group col-md-12" id="' + difference[i] + '_right">' +
                    '<input class="form-control edited_oe_ovary_right_details" name="' + textboxName + '" type="text" value="' + elementDifference[i] + '" id="' + difference[i] + '" maxlength="250" required>' +
                    '</div>'
                );
            }
        }
        if (updatedDetails.length > $('#oe_ovary_right_details').val().length) {
            $('#' + remove + '_right').remove();
        }
    });

    $(document).on('change', 'select.oe_ovary_right_details', function (e) {
        $('.edit_oe_ovary_right_details').val($('#oe_ovary_right_details').val().toString());
    });
    $(document).on('click', '.same', function (e) {
        var value = $(this).val();

        var pmh1 = '';
        var pmh2 = '';
        var pastDayDuration = '';
        var pastDayInterval = '';
        var pastDay = '';
        var pastMonth = '';
        var pastM = '';
        if (value == 'same') {
            pmh1 = $('select.past-mh-1').val();
            pmh2 = $('select.past-mh-2').val();
            pastDayDuration = $('select.past-duration-of-day').val();
            pastDayInterval = $('.past-interval-of-day').val();
            pastM = $('.past-m:checked').val();
        }

        var $radios = $('.present-m');
        $('.present-mh-1').val(pmh1);
        $('.present-mh-1').selectpicker('refresh');
        $('.present-mh-2').val(pmh2);
        $('.present-mh-2').selectpicker('refresh');
        if(typeof durationData != 'undefined'){
            durationData[pastDayDuration] = pastDayDuration;
            var durationOfDay = "<div class='col-md-3 complain-multi duration-value present-duration-data select-padding-0'><select name='mh[present_duration_of_day]' class='form-control present-duration-of-day anc-dose-val dose-data select-padding-0 duration-data width-250'>";
            durationOfDay += '<option value="">Select Duration Of Day</option>';
                $.each(durationData, function(key, value) {
                    durationOfDay +=  '<option value="' + key + '">'+value+'</option>';
                });
                durationOfDay += "</select></div>";
            $('.present-duration-data').html(durationOfDay);
            $('select.present-duration-of-day').val(pastDayDuration);
            $('.present-duration-of-day').selectize({
                create: true,
                sortField: 'text'
            });
        }else{
            $('.present-details').addClass('d-none');
            if(pastDayDuration == 'other'){
                $('.present-details').removeClass('d-none');
                $('.present-duration-details').val($('.past-duration-details').val());
            }
            $('select.present-duration-of-day').val(pastDayDuration);
            $('.present-duration-of-day').selectpicker('refresh');
        }
        $('.present-ir-regular-data').addClass('d-none');
        if(pmh2 == 'irregular'){
            $('.present-ir-regular-data').removeClass('d-none');
        }else{
            if(!$('.past-details').hasClass('d-none')){
                $('.past-details').addClass('d-none');
            }
            if(!$('.present-details').hasClass('d-none')){
                $('.present-details').addClass('d-none');
            }
        }
        if (pastM != '') {
            $radios.filter('[value=' + pastM + ']').prop('checked', true);
        } else {
            $radios.prop('checked', false);
        }
    });

    $(document).on('change', 'select.child-no', function (e) {
        var childNo = $(this).val();
        if (childNo > 12) {
            e.preventDefault();
            $(this).val(12);
            childData(childNo);
        }
        childData(childNo);

    });

    $(document).on('change', 'select.ho_type', function () {
        var value = $(this).val();
        hoType(value);
    });

    $(document).on('click', '.mtp-status', function (e) {
        var value = $(this).val();
        var dId = $(this).data('id');
        hideShow('mtp-visible', value, dId);
    });

    $(document).on('click', '.abortion-status', function (e) {
        var value = $(this).val();
        var dId = $(this).data('id');
        hideShow('abortion-visible', value, dId);
    });

    $(document).on('click', '.iui', function (e) {
        var value = $(this).val();
        if (value == 'yes') {
            $('.ho-rx-time').removeClass('d-none');
        } else {
            $('.ho-rx-time').addClass('d-none');
        }
    });

    $(document).on('click', '.ho-scopy', function (e) {
        var value = $(this).val();
        if (value == 'yes') {
            $('.ho-rx-ho-scopy').removeClass('d-none');
        } else {
            $('.ho-rx-ho-scopy').addClass('d-none');
        }
    });

    $(document).on('keyup', '.oh_mtp', function (e) {
        var mtpNo = $(this).val();
        if (mtpNo > 12) {
            e.preventDefault();
            $(this).val(12);
            mtpData(12);
            return false;
        }
        mtpData(mtpNo);
    });

    $(document).on('keyup', '.abortion-no', function (e) {
        var abortionNo = $(this).val();
        if (abortionNo > 12) {
            e.preventDefault();
            $(this).val(12);
            abortionData(12);
            return false;
        }
        abortionData(abortionNo);
    });

    $(document).on('blur', '.ut-sac', function () {
        var value = $(this).val();
        var weekNo = $('.ut-sac').val();
        var date = $('.edd-date').val();
        var oldWeekValue = $(this).data('value');
        var oldWeek = $(this).data('value', weekNo);
        // if(date != ''){
        eddWeek(date, weekNo, oldWeekValue);
        // }
        addOrRemoveClass(value);
        utGsac(value);
    });

    $(document).on('blur', '.ut-sac-2', function () {
        var value = $(this).val();
        if (value != '') {
            var weekNo = $('.ut-sac-2').val();
            var date = $('.edd-date').val();
            var oldWeekValue = $(this).data('value');
            var oldWeek = $(this).data('value', weekNo);
            // if(date != ''){
            eddWeek(date, weekNo, oldWeekValue);
            // }
            addOrRemoveClass(value);
            utGsac(value);
        }
    });

    $(document).on('change', '.lmd-date', function () {
        var value = new Date($(this).val());
        var diffDay = (new Date() - value) / 1000 / 60 / 60 / 24;
        var dateValue = diffDay.toString().split('.');
        diffDay = dateValue[0];
        if (diffDay >= 0 && diffDay != "-0") {
            diffDay = parseInt(diffDay) + 1;
        } else {
            diffDay = parseInt(diffDay) - 2;
        }
        diffDay = diffDay == -0 ? 0 : diffDay;
        $('.lmd-date-diff').removeClass('d-none');
        $('.lmd-date-diff').text(diffDay + ' Day');
        $('.lmd-date-diff-val').val(diffDay);
        $('.afcs-details').addClass('d-none');
        if(diffDay == 2 || diffDay == 3 || diffDay == 4){
            $('.afcs-details').removeClass('d-none');
        }
        if (value == 'Invalid Date') {
            $('.lmd-date-diff').addClass('d-none');
            $('.lmd-date-diff').text('');
            $('.afcs-details').addClass('d-none');
        }
    });

    $(document).on('change', 'select.infertility-type', function () {
        var value = $(this).val();
        infertilityType(value);
    });

    $(document).on('change', '.ut-type', function () {
        var value = $(this).val();
        var dId = $(this).data('id');
        $('.symbol-' + dId).text('-');
        if (value == 'g-sac') {
            $('.symbol-' + dId).text('.');
        }
        utType(value, dId);
    });

    $(document).on('keyup', '.rbs-value', function () {
        var rbsValue = $(this).val();
        rbsDetails(rbsValue);
    });

    $(document).on('keyup', '.tsh-value', function () {
        var tshValue = $(this).val();
        tshDetails(tshValue);
    });

    $(document).on('keyup', '.fbs-value', function () {
        var fbsValue = $(this).val();
        fbsDetails(fbsValue);
    });

    $(document).on('keyup', '.pp2bs-value', function () {
        var pp2bsValue = $(this).val();
        pp2bsDetails(pp2bsValue);
    });

    $(document).on('keyup', '.hb-value', function () {
        var hbValue = $(this).val();
        hbDetails(hbValue);
    });

    $(document).on('change', 'select.personal-history', function () {
        personalHistory = $(this).val();
        personalHistoryType(personalHistory);
    });

    $(document).on('click', '.hst-type-value', function () {
        var value = $(this).val();
        hstType(value);
    });

    $(document).on('click', '.hb-type', function () {
        var value = $(this).val();
        hbTypes(value);
    });

    $(document).on('change', 'select#medicine', function () {
        var parent = $(this).parent().parent().parent().parent();
        var parentId = parent.attr('id');
        // var subId = parent.children().eq(1).attr('id');
        var pparent = parent.parent().attr('id');
        var idNo = parentId.split("_", 3)[2];
        var checkId = $('#' + pparent).children().eq(idNo).attr('id');
        if (typeof checkId == 'undefined') {
            tratmentData(idNo);
        }
    });

    $(document).on('click', '.other-report-type', function () {
        var otherReportValue = $(this).val();
        otherReport(otherReportValue);
    });

    $(document).on('change', 'select.oe-no', function () {
        var oeValue = $(this).val();
        oeNumber(oeValue);
    });

    $(document).on('change', 'select.medicine', function () {
        var value = $(this).val();
        medicineData(value, $(this).data('type'));
    });

    $(document).on('click', '.anc-profile-type', function () {
        var value = $(this).val();
        ancProfileData(value);
    });

    $(document).on('click', '.early-scan-type', function () {
        var value = $(this).val();
        earlyScanData(value);
    });

    $(document).on('click', '.growth-report-type', function () {
        var value = $(this).val();
        growthReportData(value);
    });

    $(document).on('click', '.fefal-pole', function () {
        var value = $(this).val();
        var dId = $(this).data('id');
        fefalPole(value, dId);
    });

    $(document).on('click', '.health-type', function () {
        var value = $(this).val();
        var dId = $(this).data('id');
        healthType(value, dId);
    });

    $(document).on('change', 'select.p-ho-type', function () {
        var value = $(this).val();
        var dId = $(this).data('id');
        ostraticsHoType(value, dId);
    });

    $(document).on('keyup', '.crl-data', function () {
        var dId = $(this).data('id');
        var value = $(this).val();
        $('.crl-text-' + dId).text('');
        var crl = getCrlData(value);
        $('.crl-val-' + dId).val(crl.message);
        $('.crl-text-' + dId).text(crl.message);

    });

    $(document).on('change', 'select.abnormal', function () {
        abnormalDetails($(this).val(), $(this).data('type'));
    });

    $(document).on('click', '.iui-yes-no-status', function () {
        iuiYesNoStatus($(this).val(), $(this).data('type'));
    });

    $(document).on('change', 'select.seman-analysis', function () {
        semanAnalysisType($(this).val());
    });

    $(document).on('change', ".plan-management", function () {
        var ischecked = $(this).is(':checked');
        var dId = $(this).data('id');
        var type = '1';
        if (!ischecked) {
            type = '2';
        }
        planManagement(type, dId);
    });

    $(document).on('keyup', '.how-much-taken', function () {
        var value = $(this).val();
        if ($.isNumeric(value)) {
            houMuchtaken(value, $(this).data('id'));
        }
    });

    $(document).on('click', '.hcg-type-value', function () {
        var value = $(this).val();
        hcgTypeValue(value);
    });

    $(document).on('keyup', '.follicle', function (e) {
        var value = $(this).val();
        follicleType(value);
    });

    $(document).on('click', '.ovalution-type', function (e) {
        var value = $(this).val();
        $('#ovalution_yes').attr('disabled', true);
        if ($('.follicle').val() != '') {
            $('#ovalution_yes').removeAttr('disabled');
        }
        $('.follow-up-date').val('');
        $('.iui-deposit-print').addClass('d-none');
        var covalue = $('select.complaint-data').val();
        var mType = 2;
        var isOvalution = false;
        if(value == 'yes'){
            var currentDate = new Date();
            var newDate = currentDate.setDate(currentDate.getDate() + 15);
            var date = moment(newDate).format('dddd DD MMMM YYYY');
            $('.follow-up-date').val(date);
            $('.iui-deposit-print').removeClass('d-none');
            
            isOvalution = true;
        }
        complaintWiseMedicines(covalue,2,mType,'',false,false,isOvalution);
    });

    $(document).on('click','.second-marriage-life-type',function(e){
        secondMarriageData($(this).val(),$(this).data('type'));
    });

    $(document).on('click','.second-abortion-status',function(e){
        var value = $(this).val();
        var dId = $(this).data('id');
        secondAbortionHideShow('second-abortion-visible',value,dId);
    });

    $(document).on('click','.second-mtp-status',function(e){
        var value = $(this).val();
        var dId = $(this).data('id');
        secondAbortionHideShow('second-mtp-visible',value,dId);
    });

    $(document).on('change','select.second-child-no',function(e){
        var childNo = $(this).val();
        if(childNo > 12){
            e.preventDefault();
            $(this).val(12);
            secondChildData(childNo);
        }
        secondChildData(childNo);
    });

    $(document).on('keyup','.second_oh_mtp',function(e){
        var secondMtpNo = $(this).val();
        if(secondMtpNo > 12){
            e.preventDefault();
            $(this).val(12);
            secondMtpData(12);
            return false;
        }
        secondMtpData(secondMtpNo);
    });

    $(document).on('keyup','.second-abortion-no',function(e){
        var secondAbortionNo = $(this).val();
        if(secondAbortionNo > 12){
            e.preventDefault();
            $(this).val(12);
            secondAbortionData(12);
            return false;
        }
        secondAbortionData(secondAbortionNo);
    });

    $(document).on('change','select.second-p-ho-type',function(){
        var value = $(this).val();
        var dId = $(this).data('id');
        secondMerrageOstraticsHoType(value,dId);
    });

    $(document).on('click','.ps-status',function(){
        var dId = $(this).data('type');
        $('.'+dId).addClass('d-none');
        if($(this).is(':checked')){
            $('.'+dId).removeClass('d-none');
        }
    });

    $(document).on('change','select.regular-type',function(){
        var type = $(this).val();
        var dId = $(this).data('id');
        regularType(type,dId);
    });

    $(document).on('click','.vitals_status',function(){
        var value = 'no';
        var dId = $(this).data('id');
        if($(this).is(':checked')){
            value = 'yes';
        }
        vitalsData(dId,value);
    });

    $(document).on('click','.add-inducing-agent-date',function(){
        var dId = $(this).data('id');
        addInducingAgent(dId);
    });

    $(document).on('click','.remove-row',function(){
        var dId = $(this).data('id');
        $('.inducing-agent-date-data-'+dId).remove();
    });

    $(document).on('click','.contraception-status',function(){
        contraceptionStatus($(this).val(),$(this).data('classname'));
    });

    $(document).on('change','select.investigation-type',function(){
        var value = $(this).val();
        var dId = $(this).data('id');
        investigationType(value,dId);
    });

});

function investigationType(value,dId){
    $('.'+dId).addClass('d-none');
    $('.'+dId+'-data').addClass('d-none');
    if(value == '2'){
        $('.'+dId).removeClass('d-none');
    }
}
 // hide show contraception data
 function contraceptionStatus(value,dId){
    $('.'+dId).addClass('d-none');
    if(value == 'yes'){
        $('.'+dId).removeClass('d-none');
    }
}

// next appointment
function getNextAppointmentDate(appointmentId,day,date,time){
    var getUrl = window.location;
    var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
    var token = "{{csrf_token()}}";
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: baseUrl+"/next-appointment",
        dataType: 'json',
        type: 'post',
        data:{appointmentId:appointmentId,day:day,date:date,time:time,status:true}
    }).done(function(data) {
        $('.is-notAvailable').val(0);
        if(data.status == 'not-available')
        {
            var date = moment(data.date).format('dddd DD MMMM YYYY');
            $('.next-date').val(date);
            $('.next-day').val(data.diff);
            $('.next-time').val(data.time);
            $('.is-notAvailable').val(1);
        }
        if(data.status == null){
            var date = moment(data.date).format('dddd DD MMMM YYYY');
            $('.next-date').val(date);
            $('.next-day').val(data.diff);
            $('.next-time').val(data.time);
        }
    }).fail(function(error) {
        $('.form-error-msg').empty();
        if(error.responseJSON != null){
            var formError = error.responseJSON.errors;
            $.each(formError,function(key,value){
                $('.'+key).text(value);
            });
        }
    });
}

function regularType(type,dId){
    $('.'+dId).addClass('d-none');
    if(!$('.past-details').hasClass('d-none') && dId == 'past-ir-regular-data'){
        $('.past-details').addClass('d-none');
    }
    if(!$('.present-details').hasClass('d-none') && dId == 'present-ir-regular-data'){
        $('.present-details').addClass('d-none');
    }
    if(type == 'irregular'){
        $('.'+dId).removeClass('d-none');
    }
}
function follicleType(value) {
    $("#ovalution_yes").prop("checked", false);
    $('#ovalution_yes').attr('disabled', true);
    $('.iui-deposit-print').addClass('d-none');
    if (value != '') {
        $('#ovalution_yes').removeAttr('disabled');
    }
}

function follicleUpdateType(value) {

    if (value != '') {
        if ($('.ovalution-type:checked').val() == 'yes') {
            $('#ovalution_yes').attr('disabled');
            $("#ovalution_yes").prop("checked", true);
            $('.iui-deposit-print').removeClass('d-none');
            return true;
        }
        $('#ovalution_yes').removeAttr('disabled');
    }
}

function hstType(hstType) {
    if (hstType == 'yes') {
        $('.hst-status-type').removeClass('d-none');
    } else {
        $('.hst-status-type').addClass('d-none');
    }
}

// personal history
function personalHistoryType(type) {

    if (type == 2) {
        $('#growth_report_type_yes').prop('checked', 'checked');
        $("#early_scan_type_no").prop('checked', 'checked');
        $('.growth-report-data').removeClass('d-none');
        $('.early-scan-data').addClass('d-none');
    }
    if (type == 3) {
        $('#growth_report_type_no').prop('checked', 'checked');
        $("#early_scan_type_yes").prop('checked', 'checked');
        $('.early-scan-data').removeClass('d-none');
        $('.growth-report-data').addClass('d-none');
    }
}

// rbsValue
function rbsDetails(rbsValue) {
    if (rbsValue >= 150) {
        $('.rbs-details').removeClass('d-none');
    } else {
        $('.rbs-details').addClass('d-none');
    }
}

// tshValue
function tshDetails(tshValue) {
    if (tshValue >= 3.5) {
        $('.tsh-details').removeClass('d-none');
    } else {
        $('.tsh-details').addClass('d-none');
    }
}

// pp2bs value
function pp2bsDetails(pp2bsValue) {
    if (pp2bsValue >= 150) {
        $('.pp2bs-details').removeClass('d-none');
    } else {
        $('.pp2bs-details').addClass('d-none');
    }
}

// fbs value
function fbsDetails(fbsValue) {
    if (fbsValue >= 100) {
        $('.fbs-details').removeClass('d-none');
    } else {
        $('.fbs-details').addClass('d-none');
    }
}


// hb value
function hbDetails(hbValue) {
    $('.hb-extra-details').addClass('d-none');
    if (hbValue <= 8.9 && hbValue != 0) {
        $('.hb-extra-details').removeClass('d-none');
    }
}

function utType(value, dId) {
    // var oeValue = $('select.oe-no').val();
    // oeNumber(oeValue);
    if (value == 'ut') {
        // var weekNo = $('.ut-sac').val();
        var weekNo = $("input[name='oe[utdata][1][oe_ut_sac_2]']").val();
        if (weekNo == '') {
            var weekNo = $("input[name='oe[utdata][1][oe_ut_sac]']").val();
        }
        // if($('.edd-date') != '' && weekNo != ''){
        if (weekNo != '' && dId == '1') {
            eddWeek(null, weekNo);
        }
        $('.yalk-sac-' + dId).addClass('d-none');
        // $('.anc-profile').removeClass('d-none');
        $('.tsh-data').addClass('d-none');
        $('.wks-data-' + dId).removeClass('d-none');
        personalHistoryType($('select.personal-history').val());
    } else {
        if ($('.edd-date') != '' && dId == '1') {
            eddWeek(null, 0);
        }
        $('.tsh-data').removeClass('d-none');
        $('.hb-data').removeClass('d-none');
        $('.investigation-date').removeClass('d-none');
        // $('.rbs-data').removeClass('d-none');
        $('.wks-data-' + dId).addClass('d-none');
        $('.yalk-sac-' + dId).removeClass('d-none');
        // $('.anc-profile').addClass('d-none');
    }
}

function hoType(value) {
    var valueArray = ["2", "3", "4"];
    if (jQuery.inArray(value, valueArray) != -1) {
        $('.when-where').removeClass('d-none');
    } else {
        $('.when-where').addClass('d-none');
    }
}

function addOrRemoveClass(value) {
    var utSac2 = $('.ut-sac-2').val();
    if (utSac2 == '') {
        utSac2 = 0;
    }
    var utSac = $('.ut-sac').val();
    if (utSac == '') {
        utSac = 0;
    }
    if (($('.ut-type:checked').val() == 'ut' || $('.oe-ut-gsac-type').val() == 'ut') && ($.isNumeric(utSac) == true && $.isNumeric(utSac2) == true)) {
        if (value >= 14 && value <= 20) {
            if (value >= 15) {
                // $('.anc-profile').removeClass('d-none');
            }
            $('.fbs').addClass('d-none');
            // $('.tt1').removeClass('d-none');
            // $('.tt2').addClass('d-none');
            $('.usg').addClass('d-none');
            $('.growth-scane').addClass('d-none');
            $('.hst').addClass('d-none');
            $('.betnasol').addClass('d-none');
        }
        if (value >= 28 && value <= 32) {
            $('.betnasol').removeClass('d-none');
            // $('.anc-profile').addClass('d-none');
            $('.fbs').removeClass('d-none');
            // $('.tt1').addClass('d-none');
            // $('.tt2').addClass('d-none');
            $('.anomalies-scane').addClass('d-none');
            $('.early-scane').addClass('d-none');
            $('.usg').addClass('d-none');
            $('.growth-scane').removeClass('d-none');
            if (value < 30) {
                $('.hst').addClass('d-none');
            }
        }
        if (value >= 20 && value <= 24) {
            $('.betnasol').addClass('d-none');
            $('.anomalies-scane').addClass('d-none');
            // $('.anc-profile').addClass('d-none');
            $('.fbs').addClass('d-none');
            // $('.tt2').removeClass('d-none');
            // $('.tt1').addClass('d-none');
            $('.growth-scane').addClass('d-none');
            $('.hst').addClass('d-none');
            if (value == 20) {
                // $('.anc-profile').removeClass('d-none');
            }
        }
        if (value >= 18 && value <= 20) {
            if ($('.anomalies-data').val() != '') {
                $('.anomalies-data').addClass('border-highlight');
            }
            $('.nt-data').removeClass('border-highlight');
            $('.betnasol').addClass('d-none');
            $('.anomalies-scane').addClass('d-none');
            $('.anomalies-scane').removeClass('d-none');
            $('.usg').addClass('d-none');
            $('.early-scane').addClass('d-none');
            $('.hst').addClass('d-none');
            $('.growth-scane').addClass('d-none');
        }
        if (value == 10 || value == 11) {
            $('.anomalies-data').removeClass('border-highlight');
            $('.nt-data').addClass('border-highlight');
            $('.betnasol').addClass('d-none');
            $('.anomalies-scane').addClass('d-none');
            $('.usg').removeClass('d-none');
            $('.early-scane').addClass('d-none');
            $('.growth-scane').addClass('d-none');
            $('.hst').addClass('d-none');
        }
        if (value >= 6 && value <= 8) {
            $('.betnasol').addClass('d-none');
            $('.anomalies-scane').addClass('d-none');
            $('.usg').addClass('d-none');
            $('.early-scane').removeClass('d-none');
            $('.growth-scane').addClass('d-none');
            $('.hst').addClass('d-none');
        }
        if (value == '' || value == 0) {
            $('.anomalies-data').removeClass('border-highlight');
            $('.nt-data').removeClass('border-highlight');
            $('.betnasol').addClass('d-none');
            // $('.anc-profile').addClass('d-none');
            $('.fbs').addClass('d-none');
            // $('.tt1').addClass('d-none');
            // $('.tt2').addClass('d-none');
            $('.anomalies-scane').addClass('d-none');
            $('.usg').addClass('d-none');
            $('.early-scane').addClass('d-none');
            $('.growth-scane').addClass('d-none');
            $('.hst').addClass('d-none');
        }

        if (value <= 9 || (value >= 12 && value <= 17) || value >= 21) {
            $('.anomalies-data').removeClass('border-highlight');
            $('.nt-data').removeClass('border-highlight');
        }

        if (value >= 30) {
            $('.hst').removeClass('d-none');
        }
        if ((personalHistory != '' || $('select.personal-history').val() != '') && $('.ut-type:checked').val() == 'ut') {
            personalHistoryType($('select.personal-history').val());
        }

    } else {
        addDNone();
    }
}

function addDNone() {
    $('.betnasol').addClass('d-none');
    // $('.anc-profile').addClass('d-none');
    $('.fbs').addClass('d-none');
    $('.tt1').addClass('d-none');
    $('.tt2').addClass('d-none');
    $('.anomalies-scane').addClass('d-none');
    $('.usg').addClass('d-none');
    $('.early-scane').addClass('d-none');
    $('.growth-scane').addClass('d-none');
    $('.anomalies-data').removeClass('border-highlight');
    $('.nt-data').removeClass('border-highlight');
}

function eddDate(date, months, days, className) {
    // totalDays = 37 * 7;
    date.setMonth(date.getMonth() + months);
    date.setDate(date.getDate() + days);
    // totalDays = 277;
    // date.setDate(date.getDate() + totalDays);
    var date = moment(date).format('dddd DD MMMM YYYY');
    $('.' + className).val(date);
    return date;
}

// eddweek logic
function eddWeek(date, week, oldWeekValue = '') {
    // var totalWeek = 37;
    // var totalDays = 277;
    var removeDays = week * 7;
    // var removeDays = totalDays - (week * 7);
    // var days = removeDays + totalDays;
    // var weekNo = totalWeek - week;
    // var days = (totalWeek + weekNo) * 7;
    var date = $('.lmd-date').val();
    var lmdDate = new Date(date);
    var oeValue = $('.ut-type:checked').val();
    if (oeValue == 'g-sac' && date == '') {
        return false;
    }
    var utSac2 = $('.ut-sac-2').val();
    if (utSac2 == '') {
        utSac2 = 0;
    }
    var utSac = $('.ut-sac').val();
    if (utSac == '') {
        utSac = 0;
    }
    if (week == '' || removeDays == 0 || removeDays == '' || week == 0 || oeValue == 'g-sac' || $.isNumeric(utSac) == false || $.isNumeric(utSac2) == false) {
        if (lmdDate != "Invalid Date") {
            eddDate(lmdDate, 9, 7, 'edd-date');
        }
        $('.late-concept').val('');
        $('.edd-week-data').removeClass('border-highlight');
        $('.week-message').text('');
        return true;
    }
    if (date == '') {
        var nowDate = new Date();
        nowDate.setDate(nowDate.getDate() - removeDays);
        nowDatevalue = moment(nowDate).format('dddd DD MMMM YYYY');
        $('.lmd-date').val(nowDatevalue);
        var dateType = $('.date-type').val();
        if (dateType != 'anc_history') {
            eddDate(nowDate, 3, 0, 'nt-scan-date');
            eddDate(new Date($('.lmd-date').val()), 5, 0, 'anomalies-scan-date');
        }
        var nowDate = new Date($('.lmd-date').val());
        var eddDateValue = nowDate.setMonth(nowDate.getMonth() + 9);
        eddDateValue = nowDate.setDate(nowDate.getDate() + 7);
        $('.edd-date').val(moment(eddDateValue).format('dddd DD MMMM YYYY'));
        return true;
    }
    // var date = new Date(date);
    lmdDate = new Date(date);
    var eddDateValue = lmdDate.setMonth(lmdDate.getMonth() + 9);
    eddDateValue = lmdDate.setDate(lmdDate.getDate() + 7);

    // var eddDateValue = $('.edd-date').val();
    eddDateValue = new Date(eddDateValue);
    lmdDate = new Date($('.lmd-date').val());

    var diffDay = (eddDateValue - lmdDate) / (1000 * 60 * 60 * 24) + 1;
    var addDays = diffDay - removeDays;
    var totalDays = addDays + diffDay;
    date = lmdDate.setDate(lmdDate.getDate() + totalDays);
    var oldDate = new Date($('.edd-date').val());
    date = new Date(date);
    // check condition for border
    if (oldDate < date || (oldWeekValue == week && $('.edd-week-data').hasClass('border-highlight'))) {
        highlightEdd();
    } else {

        $('.late-concept').val('');
        $('.edd-week-data').removeClass('border-highlight');
        $('.week-message').text('');
    }

    date = moment(date).format('dddd DD MMMM YYYY');
    $('.edd-date').val(date);

}

// child data
function childData(childNo){
    var childData = '';
    $('.child-data').empty();
    $('.child-naturally').removeClass('d-none');
    if(childNo == 0){
        $('.child-data-parent').addClass('d-none');
        $('.child-naturally').addClass('d-none');
        $('.when-where-1').addClass('d-none');
        return true;
    }
    var type = $('select.child-no').data('type');
    var j = 2;
    if(typeof type != 'undefined'){
        j = 1;
    }
    for (i = j; i <= childNo; i++) {
        childData +=
        "<div class='row'><div class='col-md-1'><label class='vertical-form-label pr-0'>H/O :</label></div>"+
            "<div class='col-md-2'>"+
                "<div class='radio is-conceived'>"+
                    '<input type=radio name="oh[child][child_data]['+i+'][ho_term]" value="full" id="full_'+i+'"><label for="full_'+i+'">Fullterm</label>'+
                    '<input type=radio name="oh[child][child_data][' + i + '][ho_term]" value="pre" id="pre_' + i + '"><label for="pre_' + i + '">Preterm</label>' +
                "</div>" +
            "</div>" +
            "<div class='col-md-3'>" +
                '<input type=text name="oh[child][child_data][' + i + '][ho_term_details]" id="term_details_' + i + '" class="form-control" placeholder="Term Details">' +
            "</div>"+
            "<div class='col-md-3'><div class='radio is-conceived'>"+
                '<input type=radio name="oh[child][child_data]['+i+'][ho_type_value]" value="normal" checked id="normal_'+i+'"><label for="normal_'+i+'">Normal</label>'+
                '<input type=radio name="oh[child][child_data]['+i+'][ho_type_value]" value="cesarean" id="cesarean_' + i + '"><label for="cesarean_' + i + '">Cesarean</label>' +
                '<input type=radio name="oh[child][child_data]['+i+'][ho_type_value]" value="instrumental" id="instrumental_'+i+'"><label for="instrumental_'+i+'">Instrumental</label>'+
            "</div></div>"+
            "<div class='col-md-3'><div class='radio is-conceived'>"+
                '<input type=radio name="oh[child][child_data]['+i+'][ho_gender]" value="male" id="ho_male_'+i+'"><label for="ho_male_'+i+'">Male</label>'+
                '<input type=radio name="oh[child][child_data]['+i+'][ho_gender]" value="female" id="ho_female_'+i+'"><label for="ho_female_'+i+'">Female</label>'+
        "</div></div></div>" +
        "<br />" +
        "<div class='row child-data-parent'>" +
            "<div class='col-sm-1'>" +
            "</div>" +
                "<div class='col-md-3'><div class='radio is-conceived'>"+
                    '<input type=radio name="oh[child][child_data]['+i+'][ho_birth_type]" value="live_health" id="live_health_'+i+'" class="health-type" data-id="'+i+'"><label for="live_health_'+i+'">Live Health</label>'+
                    '<input type=radio name="oh[child][child_data]['+i+'][ho_birth_type]" value="stil_birth" id="stil_birth_'+i+'" class="health-type" data-id="'+i+'"><label for="stil_birth_'+i+'">Stil Birth</label>'+
                    '<input type=radio name="oh[child][child_data]['+i+'][ho_birth_type]" value="expired" id="expired_'+i+'" class="health-type" data-id="'+i+'"><label for="expired_'+i+'">Expired</label>'+
                "</div></div>"+
                "<div class='col-md-2 expired-reason-"+i+" d-none'><div class='form-group'>"+
                    '<input type="text" name="oh[child][child_data]['+i+'][expired_reason]" class="form-control" placeholder="Reason">'+
                "</div></div>"+
                "<div class='col-md-2'><div class='input-group'><span class='input-group-addon'>Live Health Year : &nbsp;</span>"+
                    '<input type="text" name="oh[child][child_data]['+i+'][live_health_year]" class="form-control">'+
                "</div></div>"+
            "</div>" +
        "</div>"+
        "<div class='row child-data-parent'>"+
            "<div class='col-md-1'></div>"+
            "<div class='col-md-4 child-naturally'>"+
                "<div class='form-group'>"+
                '<select name="oh[child][child_data]['+i+'][ho_type]" class="form-control select-padding-0 child-ho-type p-ho-type" data-id="child-when-where-'+i+'">'+
                    '<option value="1">Naturally</option>'+
                    '<option value="2">Medicine</option>'+
                    '<option value="3">IUI</option>'+
                    '<option value="4">IVF</option>'+
                '</select>'+
                "</div>"+
            "</div>"+
            "<div class='col-md-4 d-none when-where-1 child-when-where-"+i+"'>"+
                "<div class='input-group'>"+
                    "<span class='input-group-addon'>When / Where : &nbsp;</span>"+
                    '<input type="text" name="oh[child][child_data]['+i+'][when_where]" class="form-control">'+
                "</div>"+
            "</div>"+
        "</div>";
    }

    $('.child-data-parent').removeClass('d-none');
    $('.child-data').append(childData);
    $('.p-ho-type').selectpicker('refresh');

}

// mtp data
function mtpData(mtpNo){
    var mtpData = '';
    $('.mtp-data').empty();
    if(mtpNo == 0){
        $('.mtp-data-parent').addClass('d-none');
        $('.mtp-naturally').addClass('d-none');
        $('.when-where-2').addClass('d-none');
        // return true;
    }
    if(mtpNo > 0){
        $('.mtp-data-parent').removeClass('d-none');
        $('.mtp-naturally').removeClass('d-none');
    }
    var type = $('.oh_mtp').data('type');
    var j = 2;
    if(typeof type != 'undefined'){
        j = 1;
    }
    for (i = j; i <= mtpNo; i++) {
        mtpData += "<div class='row'>"+
                        "<div class='col-md-1'><label class='vertical-form-label pr-0'>MTP :</label></div>"+
                        "<div class='col-md-2'>"+
                            "<div class='radio is-conceived'>"+
                                '<input type=radio name="oh[mtp][mtp_data]['+i+'][mtp_status]" value="yes" data-id='+i+' id="history_yes_'+i+'" class="mtp-status"><label for="history_yes_'+i+'">Yes</label>'+
                                '<input type=radio name="oh[mtp][mtp_data]['+i+'][mtp_status]" value="no" checked data-id='+i+' id="history_no_'+i+'" class="mtp-status"><label for="history_no_'+i+'">No</label>'+
                            "</div>"+
                        "</div>"+

                        "<div class='row col-md-9 d-none mtp-visible-"+i+"'>"+
                            "<div class='col-md-3'>"+
                                "<div class='radio is-conceived'>"+
                                    '<input type=radio name="oh[mtp][mtp_data]['+i+'][mtp_type]" value="medically" id="medically_'+i+'"><label for="medically_'+i+'">Medically</label>'+
                                    '<input type=radio name="oh[mtp][mtp_data]['+i+'][mtp_type]" value="surgically" id="surgically_'+i+'"><label for="surgically_'+i+'">Surgically</label>'+
                                "</div>"+
                            "</div>"+
                            '<div class="col-sm-4">'+
                                '<div class="input-group">'+
                                    '<span class="input-group-addon">MOA &nbsp;</span>'+
                                    '<input type="text" name="oh[mtp][mtp_data]['+i+'][spontancous_abortion_month_of_pregancy]" class="form-control">'+
                                '</div>'+
                            '</div>'+
                            '<div class="col-sm-4">'+
                                '<div class="input-group">'+
                                    '<span class="input-group-addon">Before &nbsp;</span>'+
                                    '<input type="text" name="oh[mtp][mtp_data]['+i+'][spontancous_abortion_before]" class="form-control">'+
                                '</div>'+
                            '</div>'+
                        "</div>"+
                    "</div>"+
                    "<div class='row mtp-visible-"+i+"'>"+
                        "<div class='col-md-1'></div>"+
                        "<div class='col-md-4 mtp-naturally'>"+
                            "<div class='form-group'>"+
                            '<select name="oh[mtp][mtp_data]['+i+'][ho_type]" class="form-control select-padding-0 child-ho-type p-ho-type" data-id="mtp-when-where-'+i+'">'+
                                '<option value="1">Naturally</option>'+
                                '<option value="2">Medicine</option>'+
                                '<option value="3">IUI</option>'+
                                '<option value="4">IVF</option>'+
                            '</select>'+
                            "</div>"+
                        "</div>"+
                        "<div class='col-md-4 ml-4 d-none when-where-1 mtp-when-where-"+i+"'>"+
                            "<div class='input-group'>"+
                                "<span class='input-group-addon'>When / Where : &nbsp;</span>"+
                                '<input type="text" name="oh[mtp][mtp_data]['+i+'][when_where]" class="form-control">'+
                            "</div>"+
                        "</div>"+
                    "</div>";

    }
    $('.mtp-data').append(mtpData);
    $('.p-ho-type').selectpicker('refresh');
}

// abortion data
function abortionData(abortionNo){
    var abortionData = '';

    if(abortionNo == 0){
        $('.abortion-data-parent').addClass('d-none');
        $('.abortion-naturally').addClass('d-none');
        $('.when-where-3').addClass('d-none');
        // return true;
    }
    if(abortionNo > 0){
        $('.abortion-data-parent').removeClass('d-none');
        $('.abortion-naturally').removeClass('d-none');
    }
    $('.abortion-data').empty();
    var type = $('.abortion-no').data('type');
    var j = 2;
    if(typeof type != 'undefined'){
        j = 1;
    }
    for (i = j; i <= abortionNo; i++) {
        abortionData +=
                    "<div class='row'>"+
                        "<div class='col-md-2'><label class='vertical-form-label pr-0'>Spontancous Abortion :</label></div>"+

                        "<div class='col-md-2'><div class='radio is-conceived'>"+
                                '<input type=radio name="oh[abortion][abortion_data]['+i+'][spontancous_abortion_status]" value="yes" data-id='+i+' id="spontancous_abortion_yes_'+i+'" class="abortion-status"><label for="spontancous_abortion_yes_'+i+'">Yes</label>'+
                                '<input type=radio name="oh[abortion][abortion_data]['+i+'][spontancous_abortion_status]" value="no" checked data-id='+i+' id="spontancous_abortion_no_'+i+'" class="abortion-status"><label for="spontancous_abortion_no_'+i+'">No</label>'+
                            "</div>"+
                        "</div>"+
                        "<div class='row col-md-8 d-none abortion-visible-"+i+"'>"+
                            "<div class='col-md-3'>"+
                                "<div class='radio is-conceived'>"+
                                    '<input type=radio name="oh[abortion][abortion_data]['+i+'][spontancous_abortion_type]" value="medically" id="spontancous_abortion_medically_'+i+'"><label for="spontancous_abortion_medically_'+i+'">Medically</label>'+
                                    '<input type=radio name="oh[abortion][abortion_data]['+i+'][spontancous_abortion_type]" value="surgically" id="spontancous_abortion_surgically_'+i+'"><label for="spontancous_abortion_surgically_'+i+'">Surgically</label>'+
                                "</div>"+
                            "</div>"+
                            "<div class='col-md-4'>"+
                                "<div class='input-group'>"+
                                    "<span class='input-group-addon'>MOA &nbsp;</span>"+
                                    '<input type="text" name="oh[abortion][abortion_data]['+i+'][spontancous_abortion_month_of_pregancy]" class="form-control">'+
                                "</div>"+
                            "</div>"+
                            "<div class='col-md-4'>"+
                                "<div class='input-group'>"+
                                    "<span class='input-group-addon'>Before &nbsp;</span>"+
                                    '<input type="text" name="oh[abortion][abortion_data]['+i+'][spontancous_abortion_before]" class="form-control">'+
                                "</div>"+
                            "</div>"+
                        "</div>"+
                    "</div>"+
                    "<div class='row abortion-visible-"+i+"'>"+
                        "<div class='col-md-1'></div>"+
                        "<div class='col-md-3 abortion-naturally'>"+
                            "<div class='form-group'>"+
                            '<select name="oh[abortion][abortion_data]['+i+'][ho_type]" class="form-control select-padding-0 child-ho-type p-ho-type" data-id="abortion-when-where-'+i+'">'+
                                '<option value="1">Naturally</option>'+
                                '<option value="2">Medicine</option>'+
                                '<option value="3">IUI</option>'+
                                '<option value="4">IVF</option>'+
                            '</select>'+
                            "</div>"+
                        "</div>"+
                        "<div class='col-md-4 d-none when-where-1 abortion-when-where-"+i+"'>"+
                            "<div class='input-group'>"+
                                "<span class='input-group-addon'>When / Where : &nbsp;</span>"+
                                '<input type="text" name="oh[abortion][abortion_data]['+i+'][when_where]" class="form-control">'+
                            "</div>"+
                        "</div>"+
                        "<div class='col-md-4'>"+
                            "<div class='input-group'>"+
                                "<span class='input-group-addon'>Abortion Reason : &nbsp;</span>"+
                                '<input type="text" name="oh[abortion][abortion_data]['+i+'][reason]" class="form-control">'+
                            "</div>"+
                        "</div>"+
                    "</div>";
    }
    // $('.abortion-data-parent').removeClass('d-none');
    $('.abortion-data').append(abortionData);
    $('.p-ho-type').selectpicker('refresh');
}

function hideShow(className, value, dId) {
    if (value == 'yes') {
        $('.' + className + '-' + dId).removeClass('d-none');
    } else {
        $('.' + className + '-' + dId).addClass('d-none');
    }
}

function tratmentData(idNo, dId) {
    var id = parseInt(idNo) + 1;
    var oldId = 't_data_' + idNo;
    var newId = 'medicine_' + idNo;

    // medicine
    var medicineValue = {
        1: 'Tab innofol Hb',
        2: 'Lefritas',
        3: 'Tab Hb hope',
        4: 'Tab Ferilife XY',
        5: 'Tab Ravicap M',
        6: 'Tab Ravicap-100',
        7: 'Tab Mom Fe plus',
        8: 'Tab Tonofolic XY',
        9: 'Tab Livogen Z',
        10: 'Tab Livogen',
        11: 'Tab Dolibird',
        12: 'Tab BD123',
        13: 'Tab Folvite',
        14: 'Tab Mylsta-D',
        15: 'XTab R-folvite',
        16: 'Tab Calom veg',
        17: 'Tab Momcal',
        18: 'Tab Molecal D3',
        19: 'Tab Richkal',
        20: 'Tab Dorcical Fizz',
        21: 'TAb Caldison-500',
        22: 'Protien Powder',
        23: 'Tab Doximiom',
        24: 'Tab M-well SR(200-400)',
        25: 'Tab Posito SR(200,300,400)',
        26: 'Tab Emprogest-SR(400)',
        27: 'Tab PGyon-SR(200,300,400)',
        28: 'Tab Vifogest(300)',
        29: 'Tab R-focate plus',
        30: 'Tab Natursure',
        31: 'Tab Ecosprine(75,150)',
        32: 'Tab Duphastone',
        33: 'Tab Esvalsure(2mg)',
        34: 'Tab Progynova(1)(2mg)',
        35: 'Tab Estrabet(2ml)',
        36: 'Tab Evtab(2mg)',
        37: 'Tab Alihver',
        38: 'Cap Gestus(400mg)',
        39: 'Cap Gestobond(400mg)',
        40: 'Cap Gufipro(400mg)',
        41: 'Cap Strone(400mg)',
        42: 'Cap Fsprogest(400mg)',
        43: 'Cap natursure(400mg)',
        44: 'Gel Elnprogest(0.8%)',
        45: 'Gel Gestus(0.8%'
    }
    var madicines = "<div class='row treatment-data' id='t_data_" + id + "'><div class='col-md-3'><div class='form-group'><select name='treatment[" + id + "][medince]' class='form-control select-padding-0 dose' id='medicine'>";
    madicines += '<option value="">Select Medicines</option>';
    $.each(medicineValue, function (key, value) {
        madicines += '<option value="' + key + '">' + value + '</option>';
    });
    madicines += "</select></div></div><div class='col-md-9'><div class='row t-data' id='medicine_" + id + "'></div></div>";
    // end medicine

    // dose
    var dose = {
        "1": "OD",
        "2": "BD",
        "3": "TDS",
        "4": "ADS",
        "5":"Weekly / 1",
        "6":"Weekly / 2",
        "7":"Stat",
        "8":"SOS"
    };
    madicineData = "<div class='col-md-3'><div class='form-group'><select name='treatment[" + idNo + "][dose]' class='form-control select-padding-0 dose'>";
    madicineData += '<option value="">Select Dose</option>';
    $.each(dose, function (key, value) {
        madicineData += '<option value="' + key + '">' + value + '</option>';
    });
    madicineData += "</select></div></div>";
    // end dose

    // quantity
    madicineData += "<div class='col-md-3'><div class='input-group'><span class='input-group-addon'>Quantity : &nbsp</span>" +
        "<input type ='text' name='treatment[" + idNo + "][quantity]' class='form-control'></div></div>";
    // end quantity

    // days
    madicineData += "<div class='col-md-3'><div class='input-group'><span class='input-group-addon'>Days : &nbsp</span>" +
        "<input type ='text' name='treatment[" + idNo + "][days]' class='form-control'></div></div>";
    // end days
    $('#' + newId).html(madicineData);
    $('#' + oldId).after(madicines);
    $('.dose').selectpicker('refresh');

}

// hb types
function hbTypes(type) {
    $('.hb-details').addClass('d-none');
    if (type == 'yes') {
        $('.hb-details').removeClass('d-none');
    }
}

// other report
function otherReport(reportType) {
    $('.or-details').addClass('d-none');
    if (reportType == 'yes') {
        $('.or-details').removeClass('d-none');
    }
}

// oeNumber
function oeNumber(oeValue) {
    $('.oe-data').empty();
    var type = $('select.oe-no').data('type');
    var j = 2;
    if (typeof type != 'undefined') {
        j = 1;
    }
    var oeValueData = '';

    for (i = j; i <= oeValue; i++) {
        oeValueData += "<div class='row'>" +
            "<div class='col-md-2 ut-g-sac'><div class='radio is-conceived'>" +
            "<input type='radio' name='oe[utdata][" + i + "][ut_type]' data-id='" + i + "' value='ut' id='u" + i + "' class='ut-type' checked=true><label for='u" + i + "'>UT(WKS)</label>" +
            "<input type='radio' name='oe[utdata][" + i + "][ut_type]' data-id='" + i + "' value='g-sac' id='ug" + i + "' class='ut-type'><label for='ug" + i + "' class='ml-3'>G-sac(MM)</label>" +
            "</div></div>" +
            "<div class='col-md-1 g-sac ut-g-sac'><div class='form-group'>" +
            "<input type='text' name='oe[utdata][" + i + "][oe_ut_sac]' class='form-control max-" + i + " utsac-" + i + "' maxlength='2' data-id='" + i + "' onwheel='this.blur()' oninput='maxLengthCheck(this)'>" +
            "</div></div>" +
            "<span class='ut-g-sac-symbol symbol-" + i + "'>-</span>" +
            "<div class='col-md-1 g-sac ut-g-sac'><div class='form-group'>" +
            "<input type='text' name='oe[utdata][" + i + "][oe_ut_sac_2]' class='form-control max-" + i + " ut-2-sac-" + i + "' maxlength='2' data-id='" + i + "' onwheel='this.blur()' oninput='maxLengthCheck(this)'>" +
            "</div></div>" +
            "</div>";
        // if(utType == 'ut'){
        oeValueData += "<div class='row wks-data-" + i + "'>" +
            "<div class='col-md-1 pr-0'><label class='vertical-form-label pr-0'>FCP :</label></div>" +
            "<div class='col-md-2'><div class='radio is-conceived'>" +
            "<input type='radio' name='oe[utdata][" + i + "][fcp]' value='present' id='fcp_present_" + i + "' class='fcp_type'><label for='fcp_present_" + i + "'>Present</label>" +
            "<input type='radio' name='oe[utdata][" + i + "][fcp]' value='absent' id='fcp_absent_" + i + "' class='fcp_type'><label for='fcp_absent_" + i + "'>Absent</label>" +
            "<input type='radio' name='oe[utdata][" + i + "][fcp]' value='none' id='none_abs_" + i + "' class='fcp_type'><label for='none_abs_" + i + "'>None</label>" +
            "</div></div>" +
            "<div class='col-md-1 pr-0'><label class='vertical-form-label pr-0'>Liquor :</label></div>" +
            "<div class='col-md-3'><div class='radio is-conceived'>" +
            "<input type='radio' name='oe[utdata][" + i + "][liquor_type]' value='normal' id='liquor_normal_" + i + "' class='liquor'><label for='liquor_normal_" + i + "'>Normal</label>" +
            "<input type='radio' name='oe[utdata][" + i + "][liquor_type]' value='oligo' id='liquor_oligo_" + i + "' class='liquor'><label for='liquor_oligo_" + i + "'>Oligo</label>" +
            "<input type='radio' name='oe[utdata][" + i + "][liquor_type]' value='poly' id='liquor_poly_" + i + "' class='liquor'><label for='liquor_poly_" + i + "'>Poly</label>" +
            "<input type='radio' name='oe[utdata][" + i + "][liquor_type]' value='none' id='none_wks_position_" + i + "' class='liquor'><label for='none_wks_position_" + i + "'>None</label>" +
            "</div></div>" +
            "<div class='col-md-1 pr-0'><label class='vertical-form-label pr-0'>Position :</label></div>" +
            "<div class='col-md-4'><div class='radio is-conceived'>" +
            "<input type='radio' name='oe[utdata][" + i + "][position_type]' value='vertex' id='position_vertex_" + i + "' class='position'><label for='position_vertex_" + i + "'>Vertex</label>" +
            "<input type='radio' name='oe[utdata][" + i + "][position_type]' value='breech' id='position_breech_" + i + "' class='position'><label for='position_breech_" + i + "'>Breech</label>" +
            "<input type='radio' name='oe[utdata][" + i + "][position_type]' value='transverse' id='position_transverse_" + i + "' class='position'><label for='position_transverse_" + i + "'>Transverse</label>" +
            "<input type='radio' name='oe[utdata][" + i + "][position_type]' value='none' id='none_wks_" + i + "' class='position'><label for='none_wks_" + i + "'>None</label>" +
            "</div></div>" +
            "</div>";
        // }else{
        oeValueData += "<div class='row d-none yalk-sac-" + i + "'>" +
            "<div class='col-md-1 pr-0'><label class='vertical-form-label pr-0'>Yolk Sac :</label></div>" +
            "<div class='col-md-3'><div class='radio is-conceived'>" +
            "<input type='radio' name='oe[utdata][" + i + "][yalk_sac]' value='present' id='present_" + i + "' class='yalk_sac'><label for='present_" + i + "'>Present</label>" +
            "<input type='radio' name='oe[utdata][" + i + "][yalk_sac]' value='absent' id='absent_" + i + "' class='yalk_sac'><label for='absent_" + i + "'>Absent</label>" +
            "<input type='radio' name='oe[utdata][" + i + "][yalk_sac]' value='none' id='none_" + i + "' class='yalk_sac'><label for='none_" + i + "'>None</label>" +
            "</div></div>" +
            "<div class='col-md-1 pr-0'><label class='vertical-form-label pr-0'>Fefal Pole :</label></div>" +
            "<div class='col-md-3'><div class='radio is-conceived'>" +
            "<input type='radio' name='oe[utdata][" + i + "][fefal_pole]' value='seen' id='seen_" + i + "' class='fefal-pole' data-id='" + i + "'><label for='seen_" + i + "'>Seen</label>" +
            "<input type='radio' name='oe[utdata][" + i + "][fefal_pole]' value='notseen' id='unseen_" + i + "' class='fefal-pole' data-id='" + i + "'><label for='unseen_" + i + "'>Not Seen</label>" +
            "<input type='radio' name='oe[utdata][" + i + "][fefal_pole]' value='none' id='none_yalk_" + i + "' class='fefal-pole' data-id='" + i + "'><label for='none_yalk_" + i + "'>None</label>" +
            "</div></div>" +
            "<div class='col-md-1 pr-0'><label class='vertical-form-label pr-0'>CRL :</label></div>" +
            "<div class='col-md-2 crl-" + i + "'><div class='form-group'>" +
            "<input type='text' name='oe[utdata][" + i + "][crl]' class='form-control crl-data' data-id=" + i + ">" +
            "</div></div>" +
            "<div class='col-md-1 p-1'><span class='crl-text-" + i + "'></span><input type='hidden' name='oe[utdata][" + i + "][crl_details]' class='crl-val-" + i + "'></div>" +
            "</div>";
        // }
    }

    $('.oe-data').html(oeValueData);


}

// function medicineData(value, dId) {
//     madicineData = "";
//     idNo = 0;
//     var name = 'data[treatment]';
//     if (typeof dId == 'undefined') {
//         name = 'treatment';
//     }
//     $.each(value, function (key, index) {
//         idNo++;
//         madicineData += "<div class='row'>" +
//             "<div class='col-md-3'><div class='input-group'><span class='input-group-addon'>Medicine : &nbsp</span>" +
//             "<input type ='text' name='" + name + "[" + idNo + "][medicine]' value='" + index + "' readonly class='form-control'></div></div>";

//         // empty stomach and after meal
//         var dose = {
//             "1": "જમ્યા પછી",
//             "2": "જમ્યા પહેલાં",
//             "3": "માસિકની જગ્યાએ મુકવી"
//         };
//         madicineData += "<div class='col-md-2'><div class='form-group'><select name=" + name + "[" + idNo + "][medicine_status]' class='form-control select-padding-0 dose'>";
//         // madicineData += '<option value="">Select Medicine Time</option>';
//         $.each(dose, function (key, value) {
//             madicineData += '<option value="' + key + '">' + value + '</option>';
//         });
//         madicineData += "</select></div></div>";

//         // dose
//         var dose = {
//             "1": "OD",
//             "2": "BD",
//             "3": "TDS",
//             "4": "ADS",
//             "5": "Weekly / 1 time",
//             "6": "Weekly / 2 time"
//         };
//         madicineData += "<div class='col-md-2'><div class='form-group'><select name=" + name + "[" + idNo + "][dose]' class='form-control select-padding-0 dose'>";
//         madicineData += '<option value="">Select Dose</option>';
//         $.each(dose, function (key, value) {
//             madicineData += '<option value="' + key + '">' + value + '</option>';
//         });
//         madicineData += "</select></div></div>";
//         // end dose

//         // number
//         madicineData += "<div class='col-md-2'><div class='input-group'><span class='input-group-addon'>Days. : &nbsp</span>" +
//             "<input type ='number' name=" + name + "[" + idNo + "][no]' class='form-control'></div></div>";

//         // quantity
//         madicineData += "<div class='col-md-2'><div class='input-group'><span class='input-group-addon'>Quantity : &nbsp</span>" +
//             "<input type ='text' name=" + name + "[" + idNo + "][quantity]' class='form-control'></div></div>";
//         // end quantity
//         madicineData += "</div><div class='row'>";
//         // medicine time morning,afternoon,evening
//         var dose = {
//             "1": "Morning",
//             "2": "Afternoon",
//             "3": "Evening",
//             "4": "Night"
//         };
//         madicineData += "<div class='col-md-3'><div class='form-group'><select name=" + name + "[" + idNo + "][medicine_time][]' class='form-control select-padding-0 dose' multiple='true' title='Select Medicine Time'>";
//         $.each(dose, function (key, value) {
//             madicineData += '<option value="' + key + '">' + value + '</option>';
//         });
//         madicineData += "</select></div></div></div>";


//     });

//     $('.medicine-data').html(madicineData);
//     $('.dose').selectpicker('refresh');

// }

function medicineData(value,dId) {
    madicineData = "";
    idNo = 0;
    var name = 'data[treatment]';
    if (typeof dId == 'undefined') {
        name = 'treatment';
    }
    var getUrl = window.location;
    var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
    var avoid = "iui";
    baseUrl = baseUrl.replace(avoid,'');
    var oldMedicineData = [];
    // if ($('.old-medicine-data').val() != '') {
    //     oldMedicineData = $('.old-medicine-data').val().split(',');
    // }
    // console.log(oldMedicineData);
    var difference = [];
    // jQuery.grep(value, function(el) {
    //     if (jQuery.inArray(el, oldMedicineData) == -1) difference.push(el);
    // });
    var differenceMedicine = value.toString();
    if (value.length > oldMedicineData.length) {
        $('.medicine-loader').removeClass('d-none');
        $('.iui .selectize-dropdown').addClass('d-none');
        var id = parseInt(parseInt($('#total_medicines').val()) + 1);
        $.ajax({
            url: baseUrl+'/anc/get-existed-medicine-data',
            dataType: 'json',
            type: 'GET',
            // async: false,
            data: {
                medicine_name: differenceMedicine.toString(),
            },
        }).done(function(data) {
            var header = differenceMedicine.slice(0,3).toUpperCase();
            var notinject = "";
            var dose = {"1":"Daily","2":"Once a week","3":"Twice a week","4":"Stat","5":"SOS","6":"Alternate Day"};
            if(header == 'INJ') {
                dose = {"7":"6 hourly","8":"8 hourly","9":"12 hourly","10":"24 hourly"};
                notinject = "is-inj";
            }
            madicineData = "";
            madicineData += "<div class='row "+notinject+"' data-id=" + differenceMedicine + ">"+
                                "<div class='col-md-2'><div class='input-group'><span class='input-group-addon'>M : &nbsp</span>"+
                                "<input type ='text' name='"+ name +"["+differenceMedicine+"][medicine]' value='"+differenceMedicine.toString()+"' readonly class='form-control'></div></div>";
                var medqty = {"0":"0","1":"1","2":"2","3":"3","4":"4","5":"5"};
                // quantity
                // madicineData += "<div class='col-md-2'><div class='input-group'><span class='input-group-addon'>Quantity : &nbsp</span>"+
                //                 "<input type ='text' name='"+ name +"["+differenceMedicine+"][quantity]' class='form-control' value='" + ((data.data != null && data.data.quantity != null) ? data.data.quantity : '') + "'></div></div>";
                madicineData += "<div class='col-md-1 notinject'><div class='form-group'><select name='"+ name +"["+differenceMedicine+"][quantity]' class='form-control'>";
                $.each(medqty, function(key, value) {
                    madicineData +=  '<option value="' + key + '"' + ( key == data.data.quantity ? 'selected' : '') + '>'+value+'</option>';
                });      
                madicineData += "</select></div></div>";   
                madicineData += "<div class='col-md-1 notinject'><div class='form-group'><select name='"+ name +"["+differenceMedicine+"][quantity_2]' class='form-control'>";
                $.each(medqty, function(key, value) {
                    madicineData +=  '<option value="' + key + '"' + ( key == data.data.quantity_2 ? 'selected' : '') + '>'+value+'</option>';
                });      
                madicineData += "</select></div></div>";    
                madicineData += "<div class='col-md-1 notinject'><div class='form-group'><select name='"+ name +"["+differenceMedicine+"][quantity_3]' class='form-control'>";
                $.each(medqty, function(key, value) {
                    madicineData +=  '<option value="' + key + '"' + ( key == data.data.quantity_3 ? 'selected' : '') + '>'+value+'</option>';
                });      
                madicineData += "</select></div></div>";    
                madicineData += "<div class='col-md-1 notinject'><div class='form-group'><select name='"+ name +"["+differenceMedicine+"][quantity_4]' class='form-control'>";
                $.each(medqty, function(key, value) {
                    madicineData +=  '<option value="' + key + '"' + ( key == data.data.quantity_4 ? 'selected' : '') + '>'+value+'</option>';
                });      
                madicineData += "</select></div></div>";    
                 // medicine_time
                 var medicine_time = {"1":"IV","2":"IM","3":"SC","4":'Oral',"5":'P/V',"6":"P/A"};
                 madicineData += "<div class='col-md-3 isinject'><div class='form-group'><select name='"+ name +"["+differenceMedicine+"][medicine_time]' class='form-control select-padding-0 dose' multiple='true' title='Select Medicine Time'>";
                 $.each(medicine_time, function(key, value) {
                     madicineData +=  '<option value="' + key + '"' + ((data.data != null &&  data.data.medicine_time != null &&($.inArray(key, data.data.medicine_time) != -1)) ? 'selected' : '') + '>'+value+'</option>';
                 });
                 
                 madicineData += "</select></div></div>";       
                // end quantity
                // empty stomach and after meal
                var medicine_status = {"1":"જમ્યા પછી","2":"જમ્યા પહેલાં","3":"માસિકની જગ્યાએ મુકવી"};
                madicineData += "<div class='col-md-2 notinject'><div class='form-group'><select name='"+ name +"["+differenceMedicine+"][medicine_status]' class='form-control select-padding-0 dose medicine-status'>";
                $.each(medicine_status, function(key, value) {
                    madicineData +=  '<option value="' + key + '"' + ((data.data != null && data.data.medicine_status != null && key == data.data.medicine_status) ? 'selected' : '') + '>'+value+'</option>';
                });
                madicineData += "</select></div></div>";
                // dose
                // var dose = {"1":"OD","2":"BD","3":"TDS","4":"ADS","5":"Weekly / 1","6":"Weekly / 2","7":"Stat","8":"SOS"};
                madicineData += "<div class='col-md-2'><div class='form-group'><select name='"+ name +"["+differenceMedicine+"][dose]' class='form-control select-padding-0 dose'>";
                madicineData += '<option value="">Select Dose</option>';
                $.each(dose, function(key, value) {
                    madicineData +=  '<option value="' + key + '"' + ((data.data != null && data.data.dose != null && key == data.data.dose) ? 'selected' : '') + '>' +value+'</option>';
                });
                madicineData += "</select></div></div>";
                // end dose
                // number
                if(data.data.number == null || data.data.number == 0)
                {
                    var next_follow_date = $('.next-date').val();
                    madicineData += "<div class='col-md-2'><div class='input-group'><span class='input-group-addon'>Days:</span>"+
                    "<input type ='number' name='"+ name +"["+differenceMedicine+"][no]' class='form-control till-follow-up' value='" + dateDiffernce(next_follow_date) + "'></div></div>";
                }
                else{
                    madicineData += "<div class='col-md-2'><div class='input-group'><span class='input-group-addon'>Days:</span>"+
                    "<input type ='number' name='"+ name +"["+differenceMedicine+"][no]' class='form-control' value='" + ((data.data != null && data.data.number != null) ? data.data.number : '') + "'></div></div>";
                }
                madicineData += "<div class='col-md-4 medicine-note'><div class='form-group'><input type='text' name='treatment["+differenceMedicine+"][note]' class='form-control' placeholder='Note'></div></div>"

                madicineData += "<div class='col-md-1 medicine-data-remove'><span class=''><i class='material-icons'>close</i></span></div>";
                // madicineData += "</div><div class='row "+notinject+"' data-id=" + differenceMedicine + ">";
                
                $('.medicine-data').append(madicineData);
                $('.dose').selectpicker('refresh');
                $('.medicine-loader').addClass('d-none');
                $('.iui .selectize-dropdown').removeClass('d-none');
                // $('.treatment-data').bind('click');
                // $('.medicine').removeClass('d-none');
                // $('.selectize-dropdown-content').on('click');
        }).fail(function() {
        });
    } else {
        // console.log(differenceMedicine);
        // jQuery.grep(oldMedicineData, function(el) {
        //     if (jQuery.inArray(el, value) == -1) difference.push(el);
        // });
        // $('.row[data-id="' + difference.toString().replace(/[!@#$&()\\`.+,/\"%\-*{}[|:;'<>~?^_=\] ]/g, '_') + '"]').remove();
        // $('medicine-data.row[data-id="' + difference.toString().replace(/[!@#$&()\\`.+,/\"%\-*{}[|:;'<>~?^_=\] ]/g, '_') + '"]').remove();
        // $('#' + difference.toString().replace(/[!@#$&()\\`.+,/\"%\-*{}[|:;'<>~?^_=\] ]/g, '_')).remove();
    }
    $('.old-medicine-data').val(value.toString())
}

// function for utgsac week
function utGsac(value) {
    $('.ut-g-sac-details-1').addClass('d-none');
    $('.tt1').addClass('d-none');
    $('.tt2').addClass('d-none');
    // $('.ut-type:checked').val() == 'ut'
    var utValue = $("input[name='oe[utdata][1][ut_type]']:checked").val();
    if (typeof utValue == 'undefined') {
        utValue = $("input[name='oe[utdata][1][ut_type]']").val();
    }
    var utSac2 = $('.ut-sac-2').val();
    if (utSac2 == '') {
        utSac2 = 0;
    }
    var utSac = $('.ut-sac').val();
    if (utSac == '') {
        utSac = 0;
    }
    if (utValue == 'ut' && ($.isNumeric(utSac) == true && $.isNumeric(utSac2) == true)) {
        if (value >= 16 && value <= 20) {
            $('.tt1').removeClass('d-none');
        }
        if (value >= 24) {
            $('.tt1').removeClass('d-none');
            $('.tt2').removeClass('d-none');
            $('.ut-g-sac-details-1').addClass('d-none');
            if (value >= 25) {
                $('.ut-g-sac-details-1').removeClass('d-none');
            }
        }
    }

}

function highlightEdd() {
    $('.late-concept').val(1);
    $('.edd-week-data').addClass('border-highlight');
    $('.week-message').text('Late Conception');
}

function ancProfileData(type) {
    $('.anc-data').addClass('d-none');
    if (type == 'yes') {
        $('.anc-data').removeClass('d-none');
    }
}

function earlyScanData(type) {
    $('.early-scan-data').addClass('d-none');
    if (type == 'yes') {
        $('.early-scan-data').removeClass('d-none');
    }
}

function growthReportData(type) {
    $('.growth-report-data').addClass('d-none');
    if (type == 'yes') {
        $('.growth-report-data').removeClass('d-none');
    }
}

function maxLengthCheck(object) {
    // var value = object.value;
    // var dataId = $(object).data('id');
    // var className = 'max-'+dataId;
    // if (/[a-zA-Z!@#$&()\\`.+,/\"%\-*{}[|:;'<>~?^_=\] ]/.test(value) || value > 37 ) {
    //     return $('.'+className).val(value.substring(0, (value.length - 1)));
    // } else {
    //     return value;
    // }
}

function hbData(value) {
    $('.hb-extra-details').addClass('d-none');
    if (value > 8) {
        $('.hb-extra-details').removeClass('d-none');
    }
}

function healthType(value, dId) {
    $('.expired-reason-' + dId).addClass('d-none');
    if (value == 'expired') {
        $('.expired-reason-' + dId).removeClass('d-none');
    }
}

function ostraticsHoType(value, dId) {
    var valueArray = ["2", "3", "4"];
    if (jQuery.inArray(value, valueArray) != -1) {
        $('.'+dId).removeClass('d-none');
    } else {
        $('.'+dId).addClass('d-none');
    }
}

function fefalPole(value, dId) {
    $('.crl-details-' + dId).addClass('d-none');
    if (value == 'crl') {
        $('.crl-details-' + dId).removeClass('d-none');
    }
}

// new
function infertilityType(type) {
    $('.infertility-type-data').addClass('d-none');
    if (type == 2) {
        $('.infertility-type-data').removeClass('d-none');
    }
}

function abnormalDetails(value, dType) {
    $('.' + dType).addClass('d-none');
    if (value == '2') {
        $('.' + dType).removeClass('d-none');
    }
}

function iuiYesNoStatus(value, dType) {
    $('.' + dType).addClass('d-none');
    $('.' + dType + '-' + 'abnormal').addClass('d-none');
    if (value == 'yes') {
        $('.' + dType).removeClass('d-none');

    }
}

function semanAnalysisType(type) {
    $('.seman-analysis-type').addClass('d-none');
    if (type == '2') {
        $('.seman-analysis-type').removeClass('d-none');
    }
}

function planManagement(type, dId) {
    $('.' + dId).addClass('d-none');
    if (type == 1) {
        $('.' + dId).removeClass('d-none');
    }
}

function houMuchtaken(number, dId) {
    var hoData = '';
    var oldValue = $('.old-ho-taken').val();
    var medicineData = {
        '1': 'Ovulation induction done with Clomiphene',
        '2': 'Ovulation induction done with Letroz',
        '3': 'Ovulation induction done with both Clomiphene and letroze'
    };
    var multiple = '';
    if (dId == 'iui') {
        var medicineData = {
            '1': 'IUI-H',
            '2': 'IUI-D',
            '3': 'Both'
        };
    }
    if (dId == 'ivf') {
        multiple = 'multiple';
        var medicineData = {
            "1": "IVF Self",
            "2": "IVF-OD",
            "3": "IVF-ED"
        };
    }
    for (i = 1; i <= number; i++) {
        hoData += "<div class='col-md-4'>" +
            "<div class='form-group'>" +
            "<input type='text' name='ho_rx[" + dId + "][how_much][" + i + "]' class='form-control' onwheel='this.blur()' placeholder='Details'>" +
            "</div>" +
            "</div>" +
            "<div class='col-md-4'>" +
            "<div class='input-group'>" +
            "<span class='input-group-addon'>" +
            "When/Where : &nbsp;" +
            "</span>" +
            "<input type='text' name='ho_rx[" + dId + "][when_where][" + i + "]' class='form-control'>" +
            "</div>" +
            "</div>" +
            "<div class='col-md-4 ho-ivf-type'>" +
            "<div class='form-group'>" +
            "<select name='ho_rx[" + dId + "][type][" + i + "][]' class='form-control select-padding-0 dose' " + multiple + " title='Select Medicines'>";
        $.each(medicineData, function (key, value) {
            hoData += '<option value="' + key + '">' + value + '</option>';
        });
        hoData += "</select>" +
            "</div>" +
            "</div>";

    }
    $('.' + dId + '-data').html(hoData);
    $('.dose').selectpicker('refresh');
}

function getCrlData(week) {
    var message = '';
    switch (week) {
        case '5':
            message = "6 weeks - 5days";
            break;
        case '6':
            message = "6 weeks - 6days";
            break;
        case '7':
            message = "7 weeks";
            break;
        case '8':
            message = "7 weeks - 2days";
            break;
        case '9':
            message = "7 weeks - 3days";
            break;
        case '10':
            message = "7 weeks - 4days";
            break;
        case '11':
            message = "7 weeks - 5days";
            break;
        case '12':
            message = "7 weeks - 6days";
            break;
        case '13':
            message = "8 weeks";
            break;
        case '14':
            message = "8 weeks ";
            break;
        case '15':
            message = "8 weeks - 1day";
            break;
        case '16':
            message = "8 weeks - 2days";
            break;
        case '17':
            message = "8 weeks - 3days";
            break;
        case '18':
            message = "8 weeks - 4days";
            break;
        case '19':
            message = "8 weeks - 5days";
            break;
        case '20':
            message = "8 weeks - 6days";
            break;
        case '21':
            message = "8 weeks - 6days";
            break;
        case '22':
            message = "9 weeks";
            break;
        case '23':
            message = "9 weeks";
            break;
        case '24':
            message = "9 weeks -1day";
            break;
        case '25':
            message = "9 weeks -2days";
            break;
        case '26':
            message = "9 weeks -3days";
            break;
        case '27':
            message = "9 weeks -4days";
            break;
        case '28':
            message = "9 weeks -5days";
            break;
        case '29':
            message = "9 weeks -6days";
            break;
        case '30':
            message = "9 weeks -6days";
            break;
        case '31':
            message = "10 weeks";
            break;
        case '32':
            message = "10 weeks";
            break;
        case '33':
            message = "10 weeks -1day";
            break;
        case '34':
            message = "10 weeks -2days";
            break;
        case '35':
            message = "10 weeks -3days";
            break;
        case '36':
            message = "10 weeks -4days";
            break;
        case '37':
            message = "10 weeks -5days";
            break;
        case '38':
            message = "10 weeks -6days";
            break;
        case '39':
            message = "10 weeks -6days";
            break;
        case '40':
            message = "10 weeks -6days";
            break;
        case '41':
            message = "11 weeks";
            break;
        case '42':
            message = "11 weeks";
            break;
        case '43':
            message = "11 weeks";
            break;
        case '44':
            message = "11 weeks -1day";
            break;
        case '45':
            message = "11 weeks -2days";
            break;
        case '46':
            message = "11 weeks -3days";
            break;
        case '47':
            message = "11 weeks -4days";
            break;
        case '48':
            message = "11 weeks -5days";
            break;
        case '49':
            message = "11 weeks -6days";
            break;
        case '50':
            message = "11 weeks -6days";
            break;
        case '51':
            message = "11 weeks -6days";
            break;
        case '52':
            message = "12 weeks";
            break;
        case '53':
            message = "12 weeks";
            break;
        case '54':
            message = "12 weeks";
            break;
        case '55':
            message = "12 weeks";
            break;
        case '56':
            message = "12 weeks -2days";
            break;
        case '57':
            message = "12 weeks -3days";
            break;
        case '58':
            message = "12 weeks -4days";
            break;
        case '59':
            message = "12 weeks -5days";
            break;
        case '60':
            message = "12 weeks -6days";
            break;
        case '61':
            message = "12 weeks -6days";
            break;
        case '62':
            message = "12 weeks -6days";
            break;
        case '63':
            message = "12 weeks -6days";
            break;
        case '64':
            message = "13 weeks";
            break;
        case '65':
            message = "13 weeks";
            break;
        case '66':
            message = "13 weeks -1day";
            break;
        case '67':
            message = "13 weeks -1days";
            break;
        case '68':
            message = "13 weeks -2days";
            break;
        case '69':
            message = "13 weeks -3days";
            break;
        case '70':
            message = "13 weeks -4days";
            break;
    }
    return {
        'message': message
    };
}

function hcgTypeValue(value) {
    $('.hcg-type').addClass('d-none');
    $('.hcg-iui-type').addClass('d-none');
    $('.iui-print').addClass('d-none')
    if (value == 'yes') {
        $('.iui-print').removeClass('d-none')
        $('.hcg-type').removeClass('d-none');
        var hcgDate = $('.hcg-date').val();
        var hcgTime = $('.hcg-time').val();
        var hcgDateTime = new Date(hcgDate + ' ' + hcgTime);
        hcgDateTime = hcgDateTime.setHours(hcgDateTime.getHours() + 48);
        var hcgIuiDate = moment(hcgDateTime).format('dddd DD MMMM YYYY');
        $('.hcg-iui-date').val(hcgIuiDate);
    }
}

function secondMarriageData(value,type){
    $('.second-marriage-life').addClass('d-none');
    if(type == 1){
        $('.second-marriage-life-data').addClass('d-none');
        $('select.second-child-no').val('');
        $('.second-child-no').selectpicker('refresh');
        $('.second_oh_mtp').val(0);
        $('.second-abortion-no').val(0);
    }
    if(value == 'yes'){
        $('.second-marriage-life').removeClass('d-none');
    }
}

function secondAbortionHideShow(className, value, dId) {
    if(value == 'yes'){
        $('.'+className+'-'+dId).removeClass('d-none');
    }else{
        $('.'+className+'-'+dId).addClass('d-none');
    }
}

 // second merriage MTP
 function secondMtpData(secondMtpNo){
    var secondMtpData = '';
    $('.second-mtp-data').empty();
    if(secondMtpNo == 0){
        $('.second-mtp-data-parent').addClass('d-none');
        $('.second-mtp-naturally').addClass('d-none');
        $('.second-when-where-2').addClass('d-none');
        // return true;
    }
    if(secondMtpNo > 0){
        $('.second-mtp-data-parent').removeClass('d-none');
        $('.second-mtp-naturally').removeClass('d-none');
    }
    var type = $('.second_oh_mtp').data('type');
    var j = 2;
    if(typeof type != 'undefined'){
        j = 1;
    }
    for (i = j; i <= secondMtpNo; i++) {
        secondMtpData += "<div class='row second-marriage-life-data'>"+
                        "<div class='col-md-1'><label class='vertical-form-label pr-0'>MTP :</label></div>"+
                        "<div class='col-md-2'>"+
                            "<div class='radio is-conceived'>"+
                                '<input type=radio name="oh[second_marriage][mtp][mtp_data]['+i+'][mtp_status]" value="yes" data-id='+i+' id="second_history_yes_'+i+'" class="second-mtp-status"><label for="second_history_yes_'+i+'">Yes</label>'+
                                '<input type=radio name="oh[second_marriage][mtp][mtp_data]['+i+'][mtp_status]" value="no" checked data-id='+i+' id="second_history_no_'+i+'" class="second-mtp-status"><label for="second_history_no_'+i+'">No</label>'+
                            "</div>"+
                        "</div>"+

                        "<div class='row second-marriage-life-data col-md-9 d-none second-mtp-visible-"+i+"'>"+
                            "<div class='col-md-3'>"+
                                "<div class='radio is-conceived'>"+
                                    '<input type=radio name="oh[second_marriage][mtp][mtp_data]['+i+'][mtp_type]" value="medically" id="second_medically_'+i+'"><label for="second_medically_'+i+'">Medically</label>'+
                                    '<input type=radio name="oh[second_marriage][mtp][mtp_data]['+i+'][mtp_type]" value="surgically" id="second_surgically_'+i+'"><label for="second_surgically_'+i+'">Surgically</label>'+
                                "</div>"+
                            "</div>"+
                            "<div class='col-md-4'>"+
                                "<div class='input-group'>"+
                                    "<span class='input-group-addon'>MOA &nbsp;</span>"+
                                    '<input type="text" name="oh[second_marriage][mtp][mtp_data]['+i+'][spontancous_abortion_month_of_pregancy]" class="form-control">'+
                                "</div>"+
                            "</div>"+
                            "<div class='col-md-4'>"+
                                "<div class='input-group'>"+
                                    "<span class='input-group-addon'>Before &nbsp;</span>"+
                                    '<input type="text" name="oh[second_marriage][mtp][mtp_data]['+i+'][spontancous_abortion_before]" class="form-control">'+
                                "</div>"+
                            "</div>"+
                        "</div>"+
                    "</div>"+
                    "<div class='row second-marriage-life-data second-mtp-data-parent'>"+
                        "<div class='col-md-1'></div>"+
                        "<div class='col-md-4 second-mtp-naturally second-marriage-life-data'>"+
                            "<div class='form-group'>"+
                            '<select name="oh[second_marriage][mtp][mtp_data]['+i+'][ho_type]" class="form-control select-padding-0 child-ho-type second-p-ho-type" data-id="second-mtp-when-where-'+i+'">'+
                                '<option value="1">Naturally</option>'+
                                '<option value="2">Medicine</option>'+
                                '<option value="3">IUI</option>'+
                                '<option value="4">IVF</option>'+
                            '</select>'+
                            "</div>"+
                        "</div>"+
                        "<div class='col-md-4 d-none second-marriage-life-data second-mtp-when-where-"+i+"'>"+
                            "<div class='input-group'>"+
                                "<span class='input-group-addon'>When / Where : &nbsp;</span>"+
                                '<input type="text" name="oh[second_marriage][mtp][mtp_data]['+i+'][when_where]" class="form-control">'+
                            "</div>"+
                        "</div>"+
                    "</div>";

    }
    $('.second-mtp-data').append(secondMtpData);
    $('.second-p-ho-type').selectpicker('refresh');
}

// second abortion data
function secondAbortionData(secondAbortionNo){
    var secondAbortionData = '';

    if(secondAbortionNo == 0){
        $('.second-abortion-data-parent').addClass('d-none');
        $('.second-abortion-naturally').addClass('d-none');
        $('.second-when-where-3').addClass('d-none');
        // return true;
    }
    if(secondAbortionNo > 0){
        $('.second-abortion-data-parent').removeClass('d-none');
        $('.second-abortion-naturally').removeClass('d-none');
    }
    $('.second-abortion-data').empty();
    var type = $('.second-abortion-no').data('type');
    var j = 2;
    if(typeof type != 'undefined'){
        j = 1;
    }
    for(i = j; i <= secondAbortionNo; i++) {
        secondAbortionData +=
                    "<div class='row second-marriage-life-data'>"+
                        "<div class='col-md-2'><label class='vertical-form-label pr-0'>Spontancous Abortion :</label></div>"+

                        "<div class='col-md-2'><div class='radio is-conceived'>"+
                                '<input type=radio name="oh[second_marriage][abortion][abortion_data]['+i+'][spontancous_abortion_status]" value="yes" data-id='+i+' id="second_spontancous_abortion_yes_'+i+'" class="second-abortion-status"><label for="second_spontancous_abortion_yes_'+i+'">Yes</label>'+
                                '<input type=radio name="oh[second_marriage][abortion][abortion_data]['+i+'][spontancous_abortion_status]" value="no" checked data-id='+i+' id="second_spontancous_abortion_no_'+i+'" class="second-abortion-status"><label for="second_spontancous_abortion_no_'+i+'">No</label>'+
                            "</div>"+
                        "</div>"+
                        "<div class='row col-md-8 d-none second-abortion-visible-"+i+"'>"+
                            "<div class='col-md-3'>"+
                                "<div class='radio is-conceived'>"+
                                    '<input type=radio name="oh[second_marriage][abortion][abortion_data]['+i+'][spontancous_abortion_type]" value="medically" id="second_spontancous_abortion_medically_'+i+'"><label for="second_spontancous_abortion_medically_'+i+'">Medically</label>'+
                                    '<input type=radio name="oh[second_marriage][abortion][abortion_data]['+i+'][spontancous_abortion_type]" value="surgically" id="second_spontancous_abortion_surgically_'+i+'"><label for="second_spontancous_abortion_surgically_'+i+'">Surgically</label>'+
                                "</div>"+
                            "</div>"+
                            "<div class='col-md-4'>"+
                                "<div class='input-group'>"+
                                    "<span class='input-group-addon'>MOA &nbsp;</span>"+
                                    '<input type="text" name="oh[second_marriage][abortion][abortion_data]['+i+'][spontancous_abortion_month_of_pregancy]" class="form-control">'+
                                "</div>"+
                            "</div>"+
                            "<div class='col-md-4'>"+
                                "<div class='input-group'>"+
                                    "<span class='input-group-addon'>Before &nbsp;</span>"+
                                    '<input type="text" name="oh[second_marriage][abortion][abortion_data]['+i+'][spontancous_abortion_before]" class="form-control">'+
                                "</div>"+
                            "</div>"+
                        "</div>"+
                    "</div>"+
                    "<div class='row second-marriage-life-data second-abortion-data-parent'>"+
                        "<div class='col-md-1'></div>"+
                        "<div class='col-md-3 second-abortion-naturally second-marriage-life-data'>"+
                            "<div class='form-group'>"+
                            '<select name="oh[second_marriage][abortion][abortion_data]['+i+'][ho_type]" class="form-control select-padding-0 child-ho-type second-p-ho-type" data-id="second-abortion-when-where-'+i+'">'+
                                '<option value="1">Naturally</option>'+
                                '<option value="2">Medicine</option>'+
                                '<option value="3">IUI</option>'+
                                '<option value="4">IVF</option>'+
                            '</select>'+
                            "</div>"+
                        "</div>"+
                        "<div class='col-md-4 d-none second-marriage-life-data second-abortion-when-where-"+i+"'>"+
                            "<div class='input-group'>"+
                                "<span class='input-group-addon'>When / Where : &nbsp;</span>"+
                                '<input type="text" name="oh[second_marriage][abortion][abortion_data]['+i+'][when_where]" class="form-control">'+
                            "</div>"+
                        "</div>"+
                        "<div class='col-md-4 second-marriage-life-data'>"+
                            "<div class='input-group'>"+
                                "<span class='input-group-addon'>Abortion Reason : &nbsp;</span>"+
                                '<input type="text" name="oh[second_marriage][abortion][abortion_data]['+i+'][reason]" class="form-control">'+
                            "</div>"+
                        "</div>"+
                    "</div>";
    }
    // $('.abortion-data-parent').removeClass('d-none');
    $('.second-abortion-data').append(secondAbortionData);
    $('.second-p-ho-type').selectpicker('refresh');
}

// second child data
function secondChildData(childNo){
    var childData = '';
    $('.second-child-data').empty();
    $('.second-child-naturally').removeClass('d-none');
    if(childNo == 0){
        $('.second-child-data-parent').addClass('d-none');
        $('.second-child-naturally').addClass('d-none');
        $('.second-when-where-1').addClass('d-none');
        return true;
    }
    var type = $('select.second-child-no').data('type');
    var j = 2;
    if(typeof type != 'undefined'){
        j = 1;
    }
    for (i = j; i <= childNo; i++) {
        childData +=
        "<div class='row second-marriage-life-data'><div class='col-md-1'><label class='vertical-form-label pr-0'>H/O :</label></div>"+
            "<div class='col-md-2'>"+
                "<div class='radio is-conceived'>"+
                    '<input type=radio name="oh[second_marriage][child][child_data]['+i+'][ho_term]" value="full" id="second_full_'+i+'"><label for="second_full_'+i+'">Fullterm</label>'+
                    '<input type=radio name="oh[second_marriage][child][child_data][' + i + '][ho_term]" value="pre" id="second_pre_' + i + '"><label for="second_pre_' + i + '">Preterm</label>' +
                "</div>" +
            "</div>" +
            "<div class='col-md-3'>" +
                '<input type=text name="oh[second_marriage][child][child_data][' + i + '][ho_term_details]" id="term_details_' + i + '" class="form-control" placeholder="Term Details">' +
            "</div>"+
            "<div class='col-md-3'><div class='radio is-conceived'>"+
                '<input type=radio name="oh[second_marriage][child][child_data]['+i+'][ho_type_value]" value="normal" checked id="second_normal_'+i+'"><label for="second_normal_'+i+'">Normal</label>'+
                '<input type=radio name="oh[second_marriage][child][child_data]['+i+'][ho_type_value]" value="cesarean" id="second_cesarean_' + i + '"><label for="second_cesarean_' + i + '">Cesarean</label>' +
                '<input type=radio name="oh[second_marriage][child][child_data]['+i+'][ho_type_value]" value="instrumental" id="second_instrumental_'+i+'"><label for="second_instrumental_'+i+'">Instrumental</label>'+
            "</div></div>"+
            "<div class='col-md-3'><div class='radio is-conceived'>"+
            '<input type=radio name="oh[second_marriage][child][child_data]['+i+'][ho_gender]" value="male" id="second_ho_male_'+i+'"><label for="second_ho_male_'+i+'">Male</label>'+
            '<input type=radio name="oh[second_marriage][child][child_data]['+i+'][ho_gender]" value="female" id="second_ho_female_'+i+'"><label for="second_ho_female_'+i+'">Female</label>'+
        "</div></div></div>" +
            "<br />" +
        "<div class='row second-marriage-life-data'>" +
            "<div class='col-sm-1'>" +
            "</div>" +
            "<div class='col-md-3'><div class='radio is-conceived'>"+
                '<input type=radio name="oh[second_marriage][child][child_data]['+i+'][ho_birth_type]" value="live_health" id="second_live_health_'+i+'" class="health-type" data-id="second'+i+'"><label for="second_live_health_'+i+'">Live Health</label>'+
                '<input type=radio name="oh[second_marriage][child][child_data]['+i+'][ho_birth_type]" value="stil_birth" id="second_stil_birth_'+i+'" class="health-type" data-id="second'+i+'"><label for="second_stil_birth_'+i+'">Stil Birth</label>'+
                '<input type=radio name="oh[second_marriage][child][child_data]['+i+'][ho_birth_type]" value="expired" id="second_expired_'+i+'" class="health-type" data-id="second'+i+'"><label for="second_expired_'+i+'">Expired</label>'+
            "</div></div>"+
            "<div class='col-md-2 expired-reason-second"+i+" d-none'><div class='form-group'>"+
                '<input type="text" name="oh[second_marriage][child][child_data]['+i+'][expired_reason]" class="form-control" placeholder="Reason">'+
            "</div></div>"+
            "<div class='col-md-2'><div class='input-group'><span class='input-group-addon'>Live Health Year : &nbsp;</span>"+
                '<input type="text" name="oh[second_marriage][child][child_data]['+i+'][live_health_year]" class="form-control">'+
            "</div></div>"+
        "</div>"+
        "<div class='row second-marriage-life-data second-child-data-parent'>"+
            "<div class='col-md-1'></div>"+
            "<div class='col-md-4 second-child-naturally second-marriage-life-data'>"+
                "<div class='form-group'>"+
                '<select name="oh[second_marriage][child][child_data]['+i+'][ho_type]" class="form-control select-padding-0 child-ho-type second-p-ho-type" data-id="second-child-when-where-'+i+'">'+
                    '<option value="1">Naturally</option>'+
                    '<option value="2">Medicine</option>'+
                    '<option value="3">IUI</option>'+
                    '<option value="4">IVF</option>'+
                '</select>'+
                "</div>"+
            "</div>"+
            "<div class='col-md-4 d-none second-marriage-life-data second-child-when-where-"+i+"'>"+
                "<div class='input-group'>"+
                    "<span class='input-group-addon'>When / Where : &nbsp;</span>"+
                    '<input type="text" name="oh[second_marriage][child][child_data]['+i+'][when_where]" class="form-control">'+
                "</div>"+
            "</div>"+
        "</div>";
    }

    $('.second-child-data-parent').removeClass('d-none');
    $('.second-child-data').append(childData);
    $('.second-p-ho-type').selectpicker('refresh');

}

function secondMerrageOstraticsHoType(value,dId){
    var valueArray = ["2", "3", "4"];
    if(jQuery.inArray(value, valueArray) != -1){
        $('.'+dId).removeClass('d-none');
    }else{
        $('.'+dId).addClass('d-none');
    }
}

function vitalsData(dId,value){
    $('.'+dId).addClass('d-none');
    if(value == 'yes'){
        $('.'+dId).removeClass('d-none');
    }
}

function addInducingAgent(dId){
    var nextdId = dId + 1;
    $('.add-inducing-agent-date').data('id',nextdId);
    var dateData = "<div class='col-md-2 inducing-agent-date-data-"+nextdId+"'>"+
                        "<div class='form-group'>"+
                            "<input type='text' name='data[inducing]["+nextdId+"][date]' class='form-control datetimepicker' placeholder='Inducing Agent Date'>"+
                        "</div>"+
                    "</div>"+
                    "<a class='btn btn-primary btn-icon btn-icon-mini btn-round add-row remove-row inducing-agent-date-data-"+nextdId+"' data-id="+nextdId+"><i class='material-icons'>remove</i></a>";
    $('.inducing-date-data').append(dateData);
    $('.datetimepicker').bootstrapMaterialDatePicker({
        format: 'dddd DD MMMM YYYY',
        // minDate:new Date(),
        clearButton: true,
        time:false,
        weekStart: 1
    });
}
$(document).on('click', '.overy-popup', function () {
    var visitValue = $('.visit-value').val();
    $('.ovary-value-number').removeClass('selected-overy-td');
    $('.ovary-value').removeClass('selected-overy-td');
    var classValue = $(this).data('class');
    var ovaryValues = $('.'+classValue+'-text').val();
    var result = ovaryValues.split(',');
    checkOvaryValue(result);
    $('#overy-data-popup').modal('show');
    var overyType = $(this).data('type');
    $('.ovary-value').data('type',overyType);
    $('.ovary-value').data('class',classValue+'-text');
});

$(document).on('click','.ovary-value',function(){
    var value = $(this).data('value');
    var classValue = $(this).data('class');
    var valueData = $('.'+classValue).val();
    // var valueData = $('.'+classValue);
    $(this).addClass('selected-overy-td');
        $(this).children('.ovary-value-number').addClass('selected-overy-td');
        if(valueData != ''){
            value = valueData+','+value;
        }
    $('.'+classValue).val(value);
    // if ($(this).hasClass('selected-overy-td')) {
    //     $(this).children('.ovary-value-number').removeClass('selected-overy-td');
    //     $(this).removeClass('selected-overy-td');
    //         valueData.val(function(_, val) {
    //             if(val.includes(value + ','))
    //             {
    //                 return val.replace(value+',' , '');
    //             }
    //             else if(val.includes(','+value))
    //             {
    //                 return val.replace(','+value , '');
    //             }
    //             else{
    //                 return val.replace(value, '');
    //             }
    //     });
    //   } else {
    //     // add class selected to the clicked <li> word, add the word to the textarea
    //     $(this).addClass('selected-overy-td');
    //     $(this).children('.ovary-value-number').addClass('selected-overy-td');
    //     valueData.val(function(_, val) {
    //         if(val.length == 0)
    //         {
    //             return value;
    //         }
    //         else{
    //             return val +','+ value;
    //         }
    //     });
    //   }
});
function dateDiffernce(next_date)
{
    var date1 = new Date();
    var date2 = new Date(next_date);
    var Difference_In_Time = date2.getTime() - date1.getTime();
    return Math.ceil(Difference_In_Time / (1000 * 3600 * 24)) ;
    
}
$(document).on('click','.medicine-data-remove',function(){
    $(this).closest('div.row').remove();
})
$(document).on('blur','.next-day',function(){
    var day = $(this).val();
    var date = null;
    if (day) {
        getNextAppointmentDate(null,day,date,null);
        //set medicine-days
        // var medicine_days = dateDiffernce(dateValue);
        $('.till-follow-up').val(day);
    }
});