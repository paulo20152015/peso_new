<template>
    <div class="container-section">
        <div class="section">
            <h1>Goal</h1>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
        </div>
        <div class="section">
            <h1>Section</h1>
            <p>Lorem  has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
        </div>
        <div class="section">
            <h1>Object</h1>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum  recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
        </div>
    </div>
</template>
<style scoped>
    h1{
        font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        font-weight: 400;
        color: cornflowerblue;
        text-align: center;
    }
    p{
        font-size: 16px;
        color:darkcyan;
        padding: 1em;
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
    .section-container{

    }
</style>

