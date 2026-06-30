<style type="text/css">
.print-table{
    font-family: 'Montserrat', Arial, Tahoma, sans-serif;
    width: 100%;
    margin-top: 80px;
}
td{
    height: 25px;
    font-size: 18px;
    text-transform: capitalize;
}
.sticker-bold{
    font-weight: 900;
}
.print-first-td{
    width: 70%;
    font-weight: 900;
}
@media screen {
  div.divFooter {
    display: none;
  }
}
@page { margin-top : 200px;}
@media print {
  div.divFooter {
    position: fixed;
    bottom: 60px;
    margin-left: 10%;
  }
}
</style>
<br><br><br><br>
<table id="print-table" class="print-table">
    <tbody>
    <tr>
        <td class="print-first-td">Patient Name : {{$appointment->getPatientsDetails['name']}}</td>
        <td><span class="sticker-bold">Date : </span>{{cdate($appointment->date)->format('D, d M Y')}} </td>
    </tr>
    <tr>
        <td><span class="sticker-bold">Age</span> : {{($appointment->getPatientsDetails['age']) .' | ' .($appointment->getPatientsDetails['gender'] == 1 ? 'M' : 'F') }}</td>
        <td><span class="sticker-bold">Weight : </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Kg.</td>
    </tr>
    <tr>
        <td><span class="sticker-bold">UHID</span> : {{$appointment->getPatientsDetails['code']}}</td>
    </tr>
    </tbody>
</table>
<div class="divFooter">For any emergency, you can call on <b>9558981800</b> or you can visit the hospital.</div>