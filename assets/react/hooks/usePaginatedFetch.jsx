import React, { useState } from "react";

export default function usePaginatedFetch(url) {
	const [data, setData] = useState([]);
	const [error, setError] = useState(null);
	const [loading, setLoading] = useState(false);

	async function fetchData() {
		setLoading(true);
		try {
			const response = await fetch(url, {
				headers: {
					Accept: "application/ld+json",
				},
			});
			if (response.ok) {
				const result = await response.json();
				setData(result);
				setLoading(false);
				console.log(result);
			} else {
				setError("Failed to fetch data");
			}
		} catch (err) {
			setError(err.message);
		}
	}

	return { data, error, loading, fetchData };
}
