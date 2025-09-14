document.addEventListener('DOMContentLoaded', () => {
	const btn     = document.getElementById('generate_gpt_content');
	const prompt  = document.getElementById('gpt_content_manager_prompt');

	if (!btn || !prompt) return;

	btn.addEventListener('click', async (e) => {
		e.preventDefault();
		const text = prompt.value.trim();
		if (!text) return;

		btn.disabled = true;
		btn.textContent = 'Generation…';

		try {
			const res = await fetch(`${gcmData.root}gcm/v1/generate/`, {
				method : 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-WP-Nonce'  : gcmData.nonce,
				},
				body: JSON.stringify({ post_id: gcmData.post_id, prompt: text }),
			});

			const data = await res.json();
			if (!data.success) throw new Error(data.message || 'Ошибка');

			// Gutenberg (block editor)
			if (window.wp && wp.data && wp.data.select('core/editor')) {
				const { getCurrentPostId, getEditedPostContent } = wp.data.select('core/editor');
				const { editPost } = wp.data.dispatch('core/editor');
				const old = getEditedPostContent();
				editPost({ content: old + data.content });
			}
			// Classic editor
			else {
				const editor = window.tinymce && tinymce.get('content');
				if (editor) editor.insertContent(data.content);
				else {
					const ta = document.getElementById('content');
					if (ta) ta.value += data.content;
				}
			}
		} catch (err) {
			console.log('Error: ' + err.message);
		} finally {
			btn.disabled = false;
			btn.textContent = 'Generate';
		}
	});
});