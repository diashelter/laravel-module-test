<?php

namespace Module\Order\Domain;

class OrderEntity
{
    /**
     * @var ItemEntity[] array
     */
    private array $items;

    private function __construct(
        private readonly ?int               $id,
        private readonly int                $customerId,
        private readonly \DateTimeInterface $createdAt,
    )
    {
        $this->items = [];
    }

    public static function create(int $customerId): OrderEntity
    {
        return new self(
            id: null,
            customerId: $customerId,
            createdAt: new \DateTimeImmutable(),
        );
    }

    /**
     * @throws \Exception
     */
    public static function restore(int $id, int $customerId, int $amountInCents, string $createdAt,): OrderEntity
    {
        return new self(
            id: $id,
            customerId: $customerId,
            createdAt: new \DateTimeImmutable($createdAt),
        );
    }

    public function addItem(ItemEntity $item): void
    {
        $this->items[] = $item;
    }

    /**
     * @return ItemEntity[] array
     */
    public function getItems(): array
    {
        if (count($this->items) === 0) {
            throw new \DomainException('Items not set');
        }
        return $this->items;
    }

    public function getTotal(): int
    {
        return array_reduce($this->items, function (int $total, ItemEntity $item): int {
            $total += $item->getTotal();
            return $total;
        }, 0);
    }

    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }
}
