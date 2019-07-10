<template>
    <div class="container-section">
        <div class="section bigsection">
            <h1>Historical Background</h1>
            <p><span style="font-family: arial, helvetica, sans-serif; font-size: 10pt;">&nbsp;</span><span style="font-family: arial, helvetica, sans-serif; font-size: 10pt;">As a ratifying State to ILO Convention 88, the Philippines is mandated to create and maintain a free employment service which shall consist of a national system of employment offices under the direction of a national authority.</span></p>
            <p><span style="font-family: arial, helvetica, sans-serif; font-size: 10pt;">This mandate is religiously being carried out by the Department of Labor and Employment through the Bureau of Local Employment (BLE).</span></p>
            <p><span style="font-family: arial, helvetica, sans-serif; font-size: 10pt;">1952 - Creation of the National Employment Service (NES) under the Department of Labor and Employment by virtue of Republic Act 761 which operationalizes employment service in the country.</span></p>
            <div v-if="historyToggle == 1">
                <p><span style="font-family: arial, helvetica, sans-serif; font-size: 10pt;">1957 - A government reorganization took place and the NES was dissolved to give way to the Office of Manpower Services (OMS).</span></p>
                <p><span style="font-family: arial, helvetica, sans-serif; font-size: 10pt;">May 1974 - A major breakthrough came with the promulgation of Presidential Decree 442, otherwise known as the Labor Code of the Philippines. The Bureau of Employment Services (BES) was created as a line Bureau concerned with both local and overseas employment functions.</span></p>
                <p><span style="font-family: arial, helvetica, sans-serif; font-size: 10pt;">Extending and widening its network, the BES established forty two (42) Public Employment Offices (PEOs) in various regions with the Central Office in Manila overseeing all activities.</span></p>
                <p><span style="font-family: arial, helvetica, sans-serif; font-size: 10pt;">1982 - The year 1982 saw the employment service experiencing structural changes as a result of Executive Order No. 797. The local employment functions of the BES, including the functions of the defunct Bureau of Apprenticeship (BA) were absorbed by the Bureau of Local Employment (BLE).</span></p>
                <p><span style="font-family: arial, helvetica, sans-serif; font-size: 10pt;">June 16, 1987 - A decentralization policy by virtue of Administrative Order No. 186 was put into force, delegating BLE's line functions to the Regional Offices of the Department of Labor and Employment.</span></p>
                <p><span style="font-family: arial, helvetica, sans-serif; font-size: 10pt;">1994 - The Technical Education and Skills Development Authority (TESDA) was created under RA 7796 (TESDA Act of 1994) which absorbed the Apprenticeship functions of the Bureau of Local Employment. Eleven (11) personnel of the Bureau were transferred to TESDA.</span></p>
                <p><span style="font-family: arial, helvetica, sans-serif; font-size: 10pt;">1999 - Republic Act 8759 was enacted institutionalizing a National Facilitation Service Network thru the establishment of Public Employment Service Offices (PESOs) in every province, key cities and other strategic areas. PESO is a non-fee charging multi-employment service facility or entity. This also serves as an information hub on labor market and employment programs of the DOLE and other entities. The administration of the PESOs is lodged with the Bureau of Local Employment.</span></p>
            </div>
            <button @click="show()" class="btn btn-secondary">{{historyToggle == 0 ?'Show all':'Show less'}}</button>
        </div>
        <div class="section">
            <h1>Mission</h1>
            <p>The Bureau of Local Employment shall promote full employment by facilitating access of Filipino jobseekers to local employment opportunities through policy researches, standards setting, strategy development, labor market analysis and provision of technical assistance to regional implementors in support of employment service operations.</p>
        </div>
        <div class="section">
            <h1>Vision</h1>
            <p>BLE is the center of excellence in employment service in Asia

                BLE is well managed, dynamic and people-oriented organization</p>

        </div>
    </div>
</template>
<script>
export default {
    data(){
        return{
            historyToggle:0,
        }
    },
    methods:{
        show(){
           this.historyToggle = this.historyToggle == 0?1:0;
        }
    }
}
</script>

<style scoped>
    h1{
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        font-weight: 400;
        color: rgb(30, 47, 78);
        text-align: center;
    }
    p{
        font-size: 16px;
        color:rgb(3, 56, 56);
        padding: 0em;
    }
    .container-section{
        max-width: 1200px;
        margin:0 auto;
        display: flex;
        flex-flow: row wrap;
    }
    .section{
        border-radius: 4px;
        padding:1em;
        color: black;
        flex:12 1 30%;
        background-color: rgba(247, 234, 244, 0.993);
        min-height: 250px;
        margin: 1em;
        -webkit-box-shadow: -2px -4px 44px -13px rgba(46,19,46,1);
        -moz-box-shadow: -2px -4px 44px -13px rgba(46,19,46,1);
        box-shadow: -2px -4px 44px -13px rgba(46,19,46,1);
    }
    .bigsection{
        flex:12 1 100%;
    }
    .section-container{

    }
</style>

