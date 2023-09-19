<template>
    <img :src="'/storage/' + fieldValue" alt="" style="max-width: 200px; max-height: 50px;">
</template>

<script>
export default {
    props: ["resourceName", "field"],

    computed: {
        fieldValue() {
            return this.field.displayedAs || this.field.value
        }
    }
}
</script>
