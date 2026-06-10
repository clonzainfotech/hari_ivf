
<link rel="stylesheet" href="{{url('assets/plugins/bootstrap/css/bootstrap.min.css')}}">
<link rel="stylesheet" href="{{url('assets/css/themes.css')}}">
<style>
.print-header {
    width: 100%;
}

.print-table {
    width: 99%;
}

.print-table .font {
    font-size: 28px;
}

.print-table .doctor {
    text-align: right;
}

.print-data {
    word-wrap: break-word;
    width: 100%;
    padding: 28px 28px;
    line-height: 32px;
    text-align: justify;
}
.print-logo{
    padding: 12px;
    background-image: url('../../public/images/candor-logo.jpg');
}
.logo-image {
    width: 150px;
    height: 150px;
}
</style>
<div class="print-header">
    <table class="print-table">
        <tr>
            <td rowspan="3">
                <div class="print-logo">
                <img src="{{url('images/candor-logo.jpg')}}" class="logo-image">
            </td>
            <td class="font text-center">{{strtoupper(config('app.hospitalname2')) }}</td>
            <td class="doctor">
                <div>{{config('app.doctor') }}</div>
                <br><br>
                <div>(M.B.DGO)</div>
            </td>
        </tr>
       
    </table>
    <br/>
    <br/>
    <br/>
    <div class="print-data">
        તારીખ:  {{ \Carbon\Carbon::now()->format('d-m-Y') }}
        <br />
        <br />
        હું {{ ucwords(strtolower($patient->name)) }} મારા પેટમાં ________ માસનો ગર્ભ છે. જેમાં ____ બાળકો છે. દર્દીની સામાજિક તથા માનસિક પરિસ્થિતિ અનુસાર એક જ સંતાન જોઈતું હોય તેના માટે દર્દીને બેહોશ કરી એક બાળકને ઇન્જેક્શન વડે હૃદયમાં KCI મૂકી હૃદયના ધબકારા બંધ કરવાના છે. આ દરમિયાન બેહોશ કરવાથી સામાન્ય જોખમો જેવા કે, ઉલટી – ઉબકા ભાગ્યે જ જોવા મળતા જોખમો દર્દીનું કોમામાં જવું તથા હૃદય બંધ થઈ જવું ,દર્દીને સમજાવેલ છે. . આ દરમિયાન ભાગ્યે જ કસુવાવડ થવાની શક્યતા થાય છે. અમોને સમજાવેલ છે.
        <br />
        <br />
        દર્દીની સહી _________________________
        <br />
        દર્દીના સગા ની સહી ____________________
    </div>
    <table>
        <tr>
            <td>
                239-40-41, Bhagunagar Soc., Opp. Hans Soc., L.H. Road, Varachha, Surat - 395006.
            </td>
            <td>
                Fax / Ph : 0261 - 2548096, 2544555 E-Mail : 
            </td>
        </tr>
    </table>
</div>

