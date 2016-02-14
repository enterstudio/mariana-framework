<template id="body" style="display: none">
    <div class="vertical-center">
        <h1>"Mariana"</h1>
        <div>
            {{for(var i in this.paragraph) {}}
            <p>{{ this.paragraph[i] }}</p>
            {{ } }}
            <footer style="text-align: right"><cite title="Source Title">{{this.signature}}</cite></footer>
        </div>
    </div>
</template>