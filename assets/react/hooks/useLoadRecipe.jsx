import { useState, useCallback } from "react";

export function useLoadRecipe(url) {
	const [items, setItems] = useState([]);

	const load = useCallback(async () => {
		const response = await fetch(url, {
			headers: {
				Accept: "application/ld+json",
			},
		});
		const responseData = await response.json();
		if (response.ok) {
			setItems(responseData);
		} else {
			console.log("erreur");
		}
	}, [url]);
	return {
		items,
		load,
	};
}
