<?php

namespace App\Common\Types\Swap;

use Illuminate\Support\Str;
use InvalidArgumentException;

class FaceMapping
{
    private array $mapping; // 人脸映射关系。目标图人脸ID => 用户图人脸ID

    /**
     * @param array $mapping 映射关系数组
     * @throws InvalidArgumentException 当映射数据格式不正确时抛出异常
     */
    public function __construct(array $mapping)
    {
        $this->validateMapping($mapping);
        $this->mapping = $mapping;
    }

    /**
     * 验证映射数据的格式
     *
     * @param array $mapping
     * @throws InvalidArgumentException
     */
    private function validateMapping(array $mapping): void
    {
        foreach ($mapping as $targetId => $userId) {
            // 检查key是否为字符串
            if (!is_string($targetId)) {
                throw new InvalidArgumentException(
                    "Invalid mapping key: {$targetId}. All keys must be strings."
                );
            }
            // 必须以T#开头
            if (Str::startsWith($targetId, 'T#') === false) {
                throw new InvalidArgumentException(
                    "Invalid mapping key: {$targetId}. All keys must start with 'T#'."
                );
            }

            // 检查value是否为字符串
            if (!is_string($userId)) {
                throw new InvalidArgumentException(
                    "Invalid mapping value: {$userId}. All values must be strings."
                );
            }

            // 必须以U#开头
            if (Str::startsWith($userId, 'U#') === false) {
                throw new InvalidArgumentException(
                    "Invalid mapping value: {$userId}. All values must start with 'U#'."
                );
            }

            // 检查是否为嵌套数组
            if (is_array($userId)) {
                throw new InvalidArgumentException(
                    "Nested arrays are not allowed in mapping."
                );
            }
        }
    }

    /**
     * 获取目标图中指定人脸ID对应的用户图人脸ID
     *
     * @param string $targetFaceId
     * @return string|null
     */
    public function getUserFaceId(string $targetFaceId): ?string
    {
        return $this->mapping[$targetFaceId] ?? null;
    }

    /**
     * 获取所有映射关系
     *
     * @return array
     */
    public function getMapping(): array
    {
        return $this->mapping;
    }

    /**
     * 检查指定的目标图人脸ID是否存在映射
     *
     * @param string $targetFaceId
     * @return bool
     */
    public function hasMapping(string $targetFaceId): bool
    {
        return isset($this->mapping[$targetFaceId]);
    }

    /**
     * 获取映射数量
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->mapping);
    }

    /**
     * 转换为数组
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->mapping;
    }
}
