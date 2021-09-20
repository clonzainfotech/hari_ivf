// const { functionsIn } = require("lodash");

$(document).ready(function() {
    if (window.File && window.FileList && window.FileReader) {
        $("#unik_img_inpt_upd").on("change", function(e) {
            var unik_inpt_upd_images = e.target.files,
                filesLength = unik_inpt_upd_images.length;
            for (var i = 0; i < filesLength; i++) {
                var unikImg = unik_inpt_upd_images[i];
                var fileReader = new FileReader();
                fileReader.onload = (function(e) {
                    var file = e.target;
                    $("<span class=\"unik_img_spn_pip\">" +
                        "<img class=\"unik_img_disp_img\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
                        "<br/><span class=\"unik_img_spn_rmv\"><i class=\"fa fa-times-circle\" aria-hidden=\"true\"></i></span>" +
                        "</span>").insertAfter("#unik_img_inpt_upd");
                    $(".unik_img_spn_rmv").click(function(){
                        $(this).parent(".unik_img_spn_pip").remove();
                    });
                });
                fileReader.readAsDataURL(unikImg);
            }
        });
    } else {
        alert("Your browser doesn't support to File API")
    }

    var personalHistory = '';
    var oldValue = '';

    $(document).on('change', '.room_no', function () {
        var roomno = $(this).val();
        roomnumber(roomno);
    });

    $(document).on('click','.check-collection-report',function(){
        var password = $('.collection-password').val();
        if(password == ''){
            $('.collection-error-message').text('please enter your password.');
            return false;
        }
        var checkPassword = $('.con-password').val();
        if(password == checkPassword){
            var collectionurl = $('.collectionurl').val();
            window.location.href = collectionurl;
            return true;
        }
        $('.collection-error-message').text("Your password doesn't match our record.");
    });

    $(document).on('click','.collection-report-modal',function(){
        var route = window.location.pathname;
        if(!route.includes("collection-report")){
            $('#collection_report').modal('show');
        }
    });

    $(document).on('change','select.complaint-data',function(){
        var value = $(this).val();
        var hovalue = $('select.ho_type_value').val();
        var hoTypeValue = $('select.ho_type').val();
        var type = $(this).data('type');
        var mType = $(this).data('medicine');
        var isIvf = false;
        if(hoTypeValue == 4){
            isIvf = true;
        }
        var ovalutionType = $('.ovalution-type:checked').val();
        var checkIsSp2 = $('#progesterone:checked').val();
        var checkTrigger = $('#trigger:checked').val();
        var checkTransfer = $('#transfer:checked').val();
        var isSp2 = false;
        var isOvalution = false;
        var isTrigger = false;
        var isTransfer = false;
        if(checkIsSp2 == 'progesterone' && typeof checkIsSp2 != 'undefined'){
            isSp2 = true;
        }
        if(ovalutionType == 'yes' && typeof ovalutionType != 'undefined'){
            isOvalution = true;
        }
        if(checkTrigger == 'trigger' && typeof checkTrigger != 'undefined'){
            isTrigger = true;
        }
        if(checkTransfer == 'transfer' && typeof checkTransfer != 'undefined'){
            isTransfer = true;
        }
        // complaintWiseMedicines(value,type,mType,hovalue,isIvf,isSp2,isOvalution,isTrigger,isTransfer);
    });
    $(document).on('change','select.ho_type_value',function(){
        var hovalue = $(this).val();
        var value = $('select.complaint-data').val();
        var hoTypeValue = $('select.ho_type').val();
        var type = $(this).data('type');
        var mType = $(this).data('medicine');
        var isIvf = false;
        if(hoTypeValue == 4){
            isIvf = true;
        }
        complaintWiseMedicines(value,type,mType,hovalue,isIvf);
    });

    //room data
    function roomnumber(roomno) {
        $('.room_data').empty();
        var roomNoData = '';
        for (i = 1; i <= roomno; i++) {
            roomNoData += "<div class='row'>" +
                "<div class='col-md-6 form-group'>" +
                "<div>Room No. " + i + "</div>" +
                "<div><input type='text' name='bed_" + i + "' data-id='" + i + "' id='bed_" + i + "' class='form-control Bed-number check-number-" + i + "' placeholder='Enter Total Bed' maxlength='3' required></div>" +
                "</div></div>";
        }
        $('.room_data').html(roomNoData);
    }
});

function complaintWiseMedicines(value,type,mType,hovalue,isIvf=null,isSp2=false,isOvalution=false,isTrigger=false,isTransfer=false){
    if(value == '' || hovalue == ''){
        $('.medicine-data').html('');
        $('select.medicine-co').val('');
        var meData = "<select name='treatment[medicinedata][]' class='form-control co-value medicine medicine-co co_value_data' id='treatment-medicine'>";
        meData += '<option value="">Enter Medicine</option>';
            $.each(medicinesValue, function(key, value) {
                meData +=  '<option value="' + key + '">'+value+'</option>';
            });
            meData += "</select>";
        $('.medicine-picker').html(meData);
        $('#treatment-medicine').select2();
        // $('.medicine-co').selectize({
        //     delimiter: ',',
        //     persist: false,
        //     create: function(input) {
        //         return {
        //             value: input,
        //             text: input
        //         }
        //     }
        // });
    }
    var getUrl = window.location;
    var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
    var avoid = "anc";
    baseUrl = baseUrl.replace(avoid,'');
    var avoid = "iui";
    baseUrl = baseUrl.replace(avoid,'');
    var avoid = "/ivf";
    baseUrl = baseUrl.replace(avoid,'/');
    var str = baseUrl;
    var res = str.match(/localhost/g);
    var res2 = str.match(/127.0.0.1/g);
    if(res == 'localhost' || res2 == '127.0.0.1'){
        baseUrl = baseUrl+'/';
    }
    var baseUrl = baseUrl.replace('stich','');
    $.ajax({
        url: baseUrl+"get-complaint-wise-medicine",
        dataType: 'json',
        data:{co:value,type:mType,ho:hovalue,isIvf:isIvf,isSp2:isSp2,isOvalution:isOvalution,isTrigger:isTrigger,isTransfer:isTransfer}
    }).done(function(data) {
        var differenceMedicine = '';
        var madicineData = '';
        if(data != ''){
            var name = 'treatment';
            var mName = 'treatment';
            var dType = '';
            var ivfMClass = '';
            var medicinesClass = 'medicine';
            if(type == 1){
                dType = "data-type='1'";
                name = 'data[medicinedata]';
                ivfMClass = 'medicines-data';
                medicinesClass = '';
            }
            if(type == 2){
                dType = "data-type='1'";
                mName = 'data[treatment]';
                name = 'data[treatment]';
            }
            var meData = "<select name='"+mName+"[medicinedata][]' class='form-control co-value medicine-co "+medicinesClass+" co_value_data "+ivfMClass+"' id='treatment-medicine' placeholder='Enter medicine name' "+dType+">";
            meData += '<option value="">Enter Medicine</option>';
                $.each(medicinesValue, function(key, value) {
                    meData +=  '<option value="' + key + '">'+value+'</option>';
                });
                meData += "</select>";
            $('.medicine-picker').html(meData);
            $('select.medicine-co').val(Object.values(data.medicineArray));
            
            $('.old-medicine-data').val(Object.values(data.medicineArray).toString())
            $.each(data.medicines, function(mKey, mValue) {
                // differenceMedicine = mValue.get_medicines_data.name.toString().replace(/[!@#$&()\\`.+,/\"%\-*{}[|:;'<>~?^_=\] ]/g, '_');
                differenceMedicine = mValue.get_medicines_data.name.toString();
                
                var header = differenceMedicine.slice(0,3).toUpperCase();
            var notinject = "";
            var dose = {"1":"Daily","2":"Once a week","3":"Twice a week","4":"Stat","5":"SOS","6":"Alternate Day"};
            if(header == 'INJ') {
                dose = {"7":"6 hourly","8":"8 hourly","9":"12 hourly","10":"24 hourly"};
                notinject = "is-inj";
            }
            madicineData += "<div class='row "+notinject+"' data-id=" + differenceMedicine + ">"+
                                "<div class='col-md-2'><div class='input-group'><span class='input-group-addon'>M : &nbsp</span>"+
                                "<input type ='text' name='"+ name +"["+differenceMedicine+"][medicine]' value='"+differenceMedicine.toString()+"' readonly class='form-control'></div></div>";
                var medqty = {"0":"0","1":"1","2":"2","3":"3","4":"4","5":"5"};
                // quantity
                // madicineData += "<div class='col-md-2'><div class='input-group'><span class='input-group-addon'>Quantity : &nbsp</span>"+
                //                 "<input type ='text' name='"+ name +"["+differenceMedicine+"][quantity]' class='form-control' value='" + ((data.data != null && data.data.quantity != null) ? data.data.quantity : '') + "'></div></div>";
                madicineData += "<div class='col-md-1 notinject'><div class='form-group'><select name='"+ name +"["+differenceMedicine+"][quantity]' class='form-control'>";
                $.each(medqty, function(key, value) {
                    madicineData +=  '<option value="' + key + '"' + ( key == mValue.get_medicines_data.quantity ? 'selected' : '') + '>'+value+'</option>';
                });      
                madicineData += "</select></div></div>";   
                madicineData += "<div class='col-md-1 notinject'><div class='form-group'><select name='"+ name +"["+differenceMedicine+"][quantity_2]' class='form-control'>";
                $.each(medqty, function(key, value) {
                    madicineData +=  '<option value="' + key + '"' + ( key == mValue.get_medicines_data.quantity_2 ? 'selected' : '') + '>'+value+'</option>';
                });      
                madicineData += "</select></div></div>";    
                madicineData += "<div class='col-md-1 notinject'><div class='form-group'><select name='"+ name +"["+differenceMedicine+"][quantity_3]' class='form-control'>";
                $.each(medqty, function(key, value) {
                    madicineData +=  '<option value="' + key + '"' + ( key == mValue.get_medicines_data.quantity_3 ? 'selected' : '') + '>'+value+'</option>';
                });      
                madicineData += "</select></div></div>";    
                madicineData += "<div class='col-md-1 notinject'><div class='form-group'><select name='"+ name +"["+differenceMedicine+"][quantity_4]' class='form-control'>";
                $.each(medqty, function(key, value) {
                    madicineData +=  '<option value="' + key + '"' + ( key == mValue.get_medicines_data.quantity_4 ? 'selected' : '') + '>'+value+'</option>';
                });      
                madicineData += "</select></div></div>";    
                 // medicine_time
                 var medicine_time = {"1":"IV","2":"IM","3":"SC","4":'Oral',"5":'P/V',"6":"P/A"};
                 madicineData += "<div class='col-md-3 isinject'><div class='form-group'><select name='"+ name +"["+differenceMedicine+"][medicine_time]' class='form-control select-padding-0 dose' multiple='true' title='Select Medicine Time'>";
                 $.each(medicine_time, function(key, value) {
                     madicineData +=  '<option value="' + key + '"' + ((mValue.get_medicines_data != null &&  mValue.get_medicines_data.medicine_time != null &&($.inArray(key, mValue.get_medicines_data.medicine_time) != -1)) ? 'selected' : '') + '>'+value+'</option>';
                 });
                 
                 madicineData += "</select></div></div>";       
                // end quantity
                // empty stomach and after meal
                var medicine_status = {"1":"જમ્યા પછી","2":"જમ્યા પહેલાં","3":"માસિકની જગ્યાએ મુકવી"};
                madicineData += "<div class='col-md-2 notinject'><div class='form-group'><select name='"+ name +"["+differenceMedicine+"][medicine_status]' class='form-control select-padding-0 dose medicine-status'>";
                $.each(medicine_status, function(key, value) {
                    madicineData +=  '<option value="' + key + '"' + ((mValue.get_medicines_data != null && mValue.get_medicines_data.medicine_status != null && key == mValue.get_medicines_data.medicine_status) ? 'selected' : '') + '>'+value+'</option>';
                });
                madicineData += "</select></div></div>";
                // dose
                // var dose = {"1":"OD","2":"BD","3":"TDS","4":"ADS","5":"Weekly / 1","6":"Weekly / 2","7":"Stat","8":"SOS"};
                madicineData += "<div class='col-md-2'><div class='form-group'><select name='"+ name +"["+differenceMedicine+"][dose]' class='form-control select-padding-0 dose'>";
                madicineData += '<option value="">Select Dose</option>';
                $.each(dose, function(key, value) {
                    madicineData +=  '<option value="' + key + '"' + ((mValue.get_medicines_data != null && mValue.get_medicines_data.dose != null && key == mValue.get_medicines_data.dose) ? 'selected' : '') + '>' +value+'</option>';
                });
                madicineData += "</select></div></div>";
                // end dose
                // number
                if(mValue.get_medicines_data.number == null || mValue.get_medicines_data.number == 0)
                {
                    var next_follow_date = $('.next-date').val();
                    madicineData += "<div class='col-md-2'><div class='input-group'><span class='input-group-addon'>Days:</span>"+
                    "<input type ='number' name='"+ name +"["+differenceMedicine+"][no]' class='form-control till-follow-up' value='" + dateDiffernce(next_follow_date) + "'></div></div>";
                }
                else{
                    madicineData += "<div class='col-md-2'><div class='input-group'><span class='input-group-addon'>Days:</span>"+
                    "<input type ='number' name='"+ name +"["+differenceMedicine+"][no]' class='form-control' value='" + ((mValue.get_medicines_data != null && mValue.get_medicines_data.number != null) ? mValue.get_medicines_data.number : '') + "'></div></div>";
                }
                madicineData += "<div class='col-md-4 medicine-note'><div class='form-group'><input type='text' name='"+ name +"["+differenceMedicine+"][note]' class='form-control' placeholder='Note'></div></div>"

                madicineData += "<div class='col-md-1 medicine-data-remove'><span class=''><i class='material-icons'>close</i></span></div>";
                // madicineData += "</div><div class='row "+notinject+"' data-id=" + differenceMedicine + ">";
                madicineData += "</div>";
            });
            $('.medicine-data').html(madicineData);
            $('.dose').selectpicker('refresh');
            $('#treatment-medicine').select2();
        }
    }).fail(function() {

    });
}

// check selected data
function checkOvaryValue(arr){
    var status = 0;
    var overy_number = ['8','9','10','11','12','13','13.5','14','14.5','15','15.5','16','16.5','17','17.5','18','18.5','19','19.5','20','20.5','21','21.5','22','22.5','23','24']; 
    for(i=0; i<arr.length; i++){
        if(jQuery.inArray(arr[i], overy_number) >= 0) {
            overy_replace = arr[i].replace(".", "-");
            $('.ovary-number-'+overy_replace).addClass('selected-overy-td');
            $('.ovary-number-'+overy_replace).parents("td").addClass('selected-overy-td');
        }
    }
    return status;
}
