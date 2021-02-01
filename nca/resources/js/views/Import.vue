<template>
	<div class="container-fluid">
		<h1 class="h3 mb-4 text-gray-800">
			Импорт
		</h1>
		<div class="row">
            <div class="col">
                <div class="form-group">
                    <div class="custom-file">
                        <input
                            id="file"
                            type="file"
                            class="custom-file-input"
                            @change="uploadFile"
                        />

                        <label
                            for="file"
                            class="custom-file-label"
                            data-browse="Загрузить"
                        >
                            Файл
                        </label>
                    </div>
                </div>
            </div>
        </div>
	</div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'ImportPage',

    methods: {
        async uploadFile (event) {
            if (event.target.files.length) {
                const data = new FormData();
                data.append('props', event.target.files[[0]]);

                try {
                    await axios.post('/api/imports', data);
                    event.target.value = null;
                } catch (error) {
                    //

                    console.log(error);
                    event.target.value = null;
                }
            }
        }
    }
};
</script>
