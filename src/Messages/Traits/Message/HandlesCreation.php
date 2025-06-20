<?php

namespace Cognesy\Utils\Messages\Traits\Message;

use Cognesy\Utils\Messages\Contracts\CanProvideMessage;
use Cognesy\Utils\Messages\Message;
use Cognesy\Utils\Messages\Utils\Image;
use Cognesy\Utils\TextRepresentation;
use Exception;

trait HandlesCreation
{
    public static function make(string $role, string|array $content) : Message {
        return new Message(role: $role, content: $content);
    }

    public static function fromAny(string|array|Message $message) : static {
        return match(true) {
            is_string($message) => static::fromString($message),
            is_array($message) => static::fromArray($message),
            $message instanceof static => $message->clone(),
            default => throw new Exception('Invalid message type'),
        };
    }

    public static function fromString(string $content, string $role = self::DEFAULT_ROLE) : static {
        return new static(role: $role, content: $content);
    }

    public static function fromArray(array $message) : static {
        return new static(
            role: $message['role'] ?? 'user',
            content: $message['content'] ?? '',
            name: $message['name'] ?? '',
            metadata: $message['_metadata'] ?? [],
        );
    }

    public static function fromContent(string $role, string|array $content) : static {
        return new static(role: $role, content: $content);
    }

    public static function fromInput(string|array|object $input, string $role = '') : static {
        return match(true) {
            $input instanceof Message => $input,
            $input instanceof CanProvideMessage => $input->toMessage(),
            default => new Message($role, TextRepresentation::fromAny($input)),
        };
    }

    public static function fromImage(Image $image, string $role = '') : static {
        return new static(role: $role, content: $image->toContent());
    }

    public function clone() : self {
        $cloned = new static();
        $cloned->role = $this->role;
        $cloned->name = $this->name;
        $cloned->content = $this->content->clone();
        $cloned->metadata = $this->metadata;
        return $cloned;
    }
}