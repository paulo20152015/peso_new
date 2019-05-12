<template>
    <div class="">
        <div class="footer1">
            <div class="footer-container">
                <div class="flex">
                    <div class="part-1">
                        <h2>Reach Us</h2>
                        <p class="footext">Email: sample@gmail.com</p>
                        <p class="footext">mobile number: 0928162625</p>
                        <p class="footext">Landline : 209-03827-228</p>
                    </div>
                    <div class="part-2">
                        <h2>Our Social Media</h2>
                        <p class="footext">fb</p>
                        <p class="footext">twitter</p>
                        <p class="footext">other sites</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer2">
            <div class="footer-container">
                <p class="copyright">© Copyright of 2019-2020</p>
            </div>
        </div>
    </div>
</template>
<style scoped>
    h2{
        color: antiquewhite;
    }
    .flex{
        display: flex;
        flex-flow: row wrap;
        justify-content: flex-start;
    }
    .part-1{
        margin: 1em;
    }
    .part-2{
        margin: 1em;
    }
    .footext{
        padding-left: 1.5em;
        color: azure;
    }
    .footer1{
        padding: 2em;
        background-color: rgb(10%,10%,10%);
    }
    .footer2{
        text-align: center;
        padding: .5em;
        background-color: rgb(41, 38, 38);
    }
    .footer-container{
        max-width: 900px;
        margin: 0 auto;
    }
    .copyright{
        color: rgb(204, 177, 177);
        font-weight: 600;
    }
</style>

