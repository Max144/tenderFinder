<template>
    <div class="container">
        <div class="row justify-content-center">
            <h1>{{material.name}}</h1>
        </div>

        <div v-for="(thickness, thicknessKey) in thicknessesArray" v-bind:key="thickness.id" class="mt-3 mb-3 p-4"
             style="border: 1px solid black;">
            <div class="form-group">
                <label>
                    Толщина
                    <input type="number" class="form-control" :name="'thicknesses[' + thickness.id + ']'"
                           v-model="thickness.thickness" step="0.01">
                </label>
            </div>

            <div class="container p-4">
                <div class="row justify-content-center">
                    <h1>Размеры</h1>
                </div>

                <div v-for="(dimension, dimensionKey) in thickness.dimensions" v-bind:key="dimension.id" class="mt-3 mb-3 p-4"
                     style="border: 1px solid black;">
                    <div class="form-group">
                        <label>
                            Ширина
                            <input type="number" class="form-control"
                                   :name="'dimensions[' + thickness.id + '][' + dimension.id + '][width]'"
                                   v-model="dimension.width" step="0.01">
                        </label>
                    </div>
                    <div class="form-group">
                        <label>
                            Длина
                            <input type="number" class="form-control"
                                   :name="'dimensions[' + thickness.id + '][' + dimension.id + '][length]'"
                                   v-model="dimension.length" step="0.01">
                        </label>
                    </div>
                    <div class="form-group">
                        <label>
                            Цена
                            <input type="number" class="form-control"
                                   :name="'dimensions[' + thickness.id + '][' + dimension.id + '][price]'"
                                   v-model="dimension.price" step="0.01">
                        </label>
                    </div>
                    <div class="row">
                        <div class="col-md-9"></div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-danger" @click="deleteDimension(thicknessKey,dimensionKey)">Удалить размер</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-9">
                        <button type="button" class="btn btn-success" @click="addDimension(thicknessKey)">Добавить размер</button>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-danger" @click="deleteThickness(thicknessKey)">Удалить толщину</button>
                    </div>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <button type="button" class="btn btn-success" @click="addThickness">Добавить толщину</button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-9"></div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "MaterialThicknessForm",
    props: ['material'],
    data: function () {
        return {
            thicknessesArray: this.material.thicknesses,
            thicknessNextId: 0,
            dimensionNextId: 0,
        };
    },
    mounted: function () {
        this.thicknessNextId = Math.max.apply(Math, this.thicknessesArray.map(function(obj) { return obj.id; })) + 1
        this.dimensionNextId = Math.max.apply(Math, this.thicknessesArray.map(function(obj) {
            return Math.max.apply(Math, obj.dimensions.map(function(dimensionsObj) {
                return dimensionsObj.id;
            }));
        })) + 1
    },
    methods: {
        deleteThickness: function (num) {
            this.thicknessesArray.splice(num, 1);
        },
        addThickness: function () {
            let defaultDimensions = [
                {
                    id: this.dimensionNextId++,
                    width:1020,
                    length:2020,
                    price:0,
                }
            ];

            if (this.thicknessesArray.length) {
                defaultDimensions = this.thicknessesArray[this.thicknessesArray.length - 1].dimensions;
            }
            this.thicknessesArray.push({
                id: this.thicknessNextId++,
                thickness: 0,
                dimensions: defaultDimensions
            })
        },
        deleteDimension: function (thicknessKey, num) {
            this.thicknessesArray[thicknessKey].dimensions.splice(num, 1);
        },
        addDimension: function (thicknessKey) {
            this.thicknessesArray[thicknessKey].dimensions.push({
                id: this.dimensionNextId++,
                width: 0,
                length: 0,
                price: 0,
            })
        },
    }
}
</script>
