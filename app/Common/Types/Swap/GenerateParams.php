<?php

namespace App\Common\Types\Swap;

use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class GenerateParams
{
    /**
     * @var string 目标图片
     */
    private string $targetImage;

    /**
     * @var string 用户图片
     */
    private string $userImage;

    /**
     * @var FaceMappingList 人脸映射列表
     */
    private FaceMappingList $faceMappingList;

    public function __construct(string $targetImage, string $userImage, array $faceMappingList)
    {
        $validator = Validator::make(
            [
                'target_image' => $targetImage,
                'user_image' => $userImage,
            ],
            [
                'target_image' => ['required', 'string', 'url'],
                'user_image' => ['required', 'string', 'url'],
            ],
            [
                'target_image.url' => 'Target image must be a valid URL',
                'user_image.url' => 'User image must be a valid URL',
            ]
        );

        if ($validator->fails()) {
            throw new InvalidArgumentException(
                $validator->errors()->first()
            );
        }

        $this->targetImage = $targetImage;
        $this->userImage = $userImage;
        $this->faceMappingList = FaceMappingList::fromArray($faceMappingList);
    }

    public function toArray(): array
    {
        return [
            'target_image' => $this->targetImage,
            'user_image' => $this->userImage,
            'face_mapping_list' => $this->faceMappingList->toArray(),
        ];
    }

}
