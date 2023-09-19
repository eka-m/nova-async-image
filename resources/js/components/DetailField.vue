<template>
    <PanelItem :index="index" :field="field">
        <h4 class="font-normal"><span>{{ field.name }}</span></h4>
        <div class="py-4">
            <img :src="'/storage/' + field.value" alt="">
        </div>
    </PanelItem>

</template>

<script>
export default {
    props: ["index", "resource", "resourceName", "resourceId", "field"]
}
</script>
