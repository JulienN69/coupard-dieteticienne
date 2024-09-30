import { useCallback, useState } from "react";

export default function useLoadData(url) {
	const [items, setItems] = useState([]);

	const load = useCallback(async () => {
		const response = await fetch(url, {
			headers: {
				Accept: "application/ld+json",
			},
		});
		const responseData = await response.json();
		if (response.ok) {
			setItems(responseData["hydra:member"]);
		} else {
			console.log("erreur");
		}
	}, [url]);
	return {
		items,
		load,
	};
}
