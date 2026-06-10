
<link rel="stylesheet" href="{{url('assets/plugins/bootstrap/css/bootstrap.min.css')}}">
<link rel="stylesheet" href="{{url('assets/css/themes.css')}}">
<style>
    .print-header {
    width: 100%;
}

.print-table {
    width: 100%;
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
        દર્દીને કુટુંબ નિયોજનનું ઓપરેશન_____વર્ષ પહેલા કરાવેલ છે. હવે મારા બીજા લગ્ન થયા હોવાથી સંતાનની ઈચ્છા હોય. કુટુંબ નિયોજન ઓપરેશન જે નળીઓને કાપી નાખેલું હોય એને પાછી જોડવાનું (Tubal Recanalisation) કરવા માટે રાધા હોસ્પિટલમાં દાખલ થયેલ છે. આ ઓપરેશન પછી બાળક રહી જ જશે એવી 100%  ગેરંટી આપવામાં આવતી નથી કેમ કે બાળક રહેવા માટે માત્ર નળીઓ જ જવાબદાર હોતી નથી. નળીઓના ઓપરેશન પછી નળીઓ ખુલ્લી થઈ જાય એવું જરૂરી નથી . નળીઓ રીપેર કર્યા પછી નળીનું રૂઝ સંપૂર્ણપણે આવી જાય અને કુદરતે બનાવી એવી નળીઓ થઈ જાય એવું જરૂરી નથી. નળીઓની લંબાઈ પણ આગળના ઓપરેશનમાં જેટલી કપાયેલી હોય તેના પર આધાર રાખે છે. ઓપરેશનના છ મહિનામાં બાળક રહેવાની સંભાવના વધારે હોય છે. બાળક નળીમાં રહેવાની શક્યતા કુદરતી રીત કરતા વધારે હોય છે. આથી મહિના પર એક પણ દિવસ જાય તો તરત જ પ્રેગનેન્સી ચેક કરવી અને સોનોગ્રાફી કરાવવી . ઓપરેશન દરમિયાન બીજી ગંભીર સમસ્યા પણ થઈ શકે છે. વધારે પડતું લોહી વહી જવું , ફેફસાની સમસ્યા, લોહીનું દબાણ વધી જવું, જેને કારણે દર્દીની સારવાર તાત્કાલીક કરવી પડે તથા એમની વધારે સારવાર માટે બીજી હોસ્પિટલમાં સ્થળાંતર કરવું પડે. વધારે લોહી વહી જવાને કારણે લોહી આપવાની જરૂર પડી શકે. આ બધી વિગતો દર્દી તથા તેમના સગા ને અમે અમારી ભાષામાં સમજાવેલ છે આ બાબતમાં ડોક્ટર તથા હોસ્પિટલનો સ્ટાફ જવાબદાર રહેશે નહીં જેની અમે સંમતિ આપીએ છીએ.
        <br />
        <br />
        દર્દીની સહી _________________________
        <br />
        દર્દીના સગા ની સહી ____________________
    </div>
</div>

