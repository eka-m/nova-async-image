<template>
	<DefaultField :field="field" :errors="errors" :show-help-text="showHelpText" :full-width-content="fullWidthContent">
		<template #field>
			<div class="grid grid-cols-4 gap-x-6 gap-y-2">
				<div class="h-full flex items-start justify-center" v-if="value">
					<div class="relative w-full">
						<button @click.prevent="remove" type="button" class="rounded-full shadow bg-white dark:bg-gray-800 text-center flex items-center justify-center h-[20px] w-[21px] absolute z-20 top-[-10px] right-[-9px] v-popper--has-tooltip" dusk="cover-delete-link">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="20" height="20" class="inline-block text-gray-800 dark:text-gray-200" role="presentation">
								<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
							</svg>
						</button>
						<div class="bg-gray-50 dark:bg-gray-700 relative aspect-square flex items-center justify-center border-2 border-gray-200 dark:border-gray-700 overflow-hidden rounded-lg">
							<img :src="'/storage/' + value" class="aspect-square object-scale-down" />
						</div>
						<p class="font-semibold text-xs mt-1">{{ value }}</p>
					</div>
				</div>
			</div>
			<div class="space-y-4">
				<div class="relative">
					<input class="visually-hidden w-full h-full absolute top-0 right-0 bottom-0 left-0 m-auto z-10" type="file" accept="image/*" ref="image_input" />
					<div class="space-y-4" @click="$refs.image_input.click()">
						<label class="block cursor-pointer p-4 bg-gray-50 dark:bg-gray-900 dark:hover:bg-gray-900 border-4 border-dashed hover:border-gray-300 dark:border-gray-700 dark:hover:border-gray-600 rounded-lg">
							<div class="flex items-center space-x-4 pointer-events-none">
								<p class="text-center pointer-events-none">
									<span class="shadow relative bg-primary-500 hover:bg-primary-400 text-white dark:text-gray-900 cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 inline-flex items-center justify-center h-9 px-3 shadow relative bg-primary-500 hover:bg-primary-400 text-white dark:text-gray-900">Choose File</span>
								</p>
								<div class="pointer-events-none text-center text-sm text-gray-500 dark:text-gray-400 font-semibold">Drop file or click to choose</div>
							</div>
						</label>
					</div>
				</div>
			</div>
		</template>
	</DefaultField>
</template>
<script>
import { FormField, HandlesValidationErrors } from "laravel-nova"

export default {
	mixins: [FormField, HandlesValidationErrors],
	props: ["resourceName", "resourceId", "field"],
	mounted() {
		this.$refs.image_input.addEventListener("change", this.upload)
	},
	methods: {
		upload(event) {
			console.log("YES")
			const imageFile = event.target.files[0]
			if (imageFile) {
				const formData = new FormData()
				formData.append("image", imageFile)
				formData.append("path", this.field.folder)
				formData.append("disk", this.field.disk)
				fetch("/eka/async/image/upload", {
					method: "POST",
					body: formData
				})
					.then(response => response.json())
					.then(data => (this.value = data.path))
					.catch(error => {
						console.log("ERROR")
						console.log(error)
					})
			}
		},

		remove() {
			const formData = new FormData()
			formData.append("image", this.value)
			formData.append("path", this.field.folder)
			formData.append("disk", this.field.disk)
			fetch("/eka/async/image/remove", { method: "POST", body: formData })
				.then(response => response.json())
				.then(data => {
					this.value = null
					console.log(data.message)
				})
				.catch(error => {
					console.log("ERROR", error)
				})
		},
		/*
		 * Set the initial, internal value for the field.
		 */
		setInitialValue() {
			this.value = this.field.value || ""
		},

		/**
		 * Fill the given FormData object with the field's internal value.
		 */
		fill(formData) {
			formData.append(this.fieldAttribute, this.value || "")
		}
	}
}
</script>
