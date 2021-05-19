<?php


namespace api\modules\v1\controllers;


use common\controllers\RestAuthController;
use common\models\DocumentType;
use common\models\EquipmentCategory;
use common\models\EquipmentFunction;
use common\models\Organization;
use common\models\PlacementType;
use common\models\Post;
use common\models\SampleCategory;
use common\models\TestEquipmentGroup;
use common\models\TypeOwn;

class DictionaryController extends RestAuthController
{

    public function behaviors(
        $verbs_props = [
            'index' => ['GET'],
        ]
    )
    {
        return parent::behaviors($verbs_props);
    }

    public function actionIndex()
    {
        $equipment_categories = EquipmentCategory::find()->all();
        $equipment_functions = EquipmentFunction::find()->all();
        $placement_types = PlacementType::find()->all();
        $type_owns = TypeOwn::find()->all();
        $test_groups = TestEquipmentGroup::find()->all();
        $sample_categories = SampleCategory::find()->all();
        $document_types = DocumentType::find()->all();
        $posts = Post::find()->all();
        $organizations = Organization::find()->all();

        return $this->success([
            'equipment_categories' => $equipment_categories,
            'equipment_functions' => $equipment_functions,
            'placement_types' => $placement_types,
            'type_owns' => $type_owns,
            'test_groups' => $test_groups,
            'sample_categories' => $sample_categories,
            'document_types' => $document_types,
            'posts' => $posts,
            'organizations' => $organizations,
        ]);
    }

}