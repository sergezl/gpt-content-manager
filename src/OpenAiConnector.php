<?php
/**
 * Class OpenAiConnector
 *
 * Handles the interaction with the OpenAI API.
 *
 * @package GptContentManager
 */

namespace GptContentManager;

use OpenAI as OpenAI;

class OpenAiConnector {

	/**
	 * The OpenAI client instance.
	 *
	 * @var OpenAI\Client $client The OpenAI client instance.
	 */
	protected OpenAI\Client $client;

	/**
	 * OpenAiConnector constructor.
	 *
	 * @param string $apiKey The API key for OpenAI.
	 */
	public function __construct(string $apiKey) {
		$this->client = OpenAI::client($apiKey);
	}

	/**
	 * Generate response from the OpenAI API.
	 *
	 * @param string $userMessage The message provided by the user.
	 * @return string The generated response from the OpenAI API.
	 */
	public function generateResponse(string $userMessage): string {
		$result = $this->client->chat()->create([
			'model' => 'gpt-4',
			'messages' => [
				['role' => 'user', 'content' => $userMessage],
			],
		]);

		return $result->choices[0]->message->content;
	}
}
