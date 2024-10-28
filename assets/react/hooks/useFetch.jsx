import React, { useCallback, useState } from "react";

async function jsonLdFetch(url, method = "GET", data = null) {
	const params = {
		method: method,
		headers: {
			Accept: "application/ld+json",
			"Content-Type": "application/ld+json",
		},
	};
	if (data) {
		params.body = JSON.stringify(data);
	}
	const response = await fetch(url, params);
	if (response.status === 204) {
		return null;
	}
	const responseData = await response.json();
	if (response.ok) {
		return responseData;
	} else {
		throw responseData;
	}
}

export function useFetch(url, method = "POST", callback) {
	const [errors, setErrors] = useState({});
	const [loading, setLoading] = useState(false);
	const load = useCallback(
		async (data = null) => {
			setLoading(true);
			try {
				const response = await jsonLdFetch(url, method, data);
				if (callback) {
					callback(response);
				}
			} catch (error) {
				setErrors(
					error.violations.reduce((acc, violation) => {
						acc[violation.propertyPath] = violation.message;
						return acc;
					}, {})
				);
			}
			setLoading(false);
		},
		[url, method, callback]
	);
	const clearError = useCallback(
		(name) => {
			if (errors[name]) {
				setErrors((errors) => ({ ...errors, [name]: null }));
			}
		},
		[errors]
	);
	return { loading, errors, load, clearError };
}

export default function useLoadData(url) {
	const [items, setItems] = useState([]);
	const [next, setNext] = useState(null);
	const [prev, setPrev] = useState(null);
	const [currentPage, setCurrentPage] = useState(1);
	const [numberPage, setNumberPage] = useState(1);
	const [loading, setLoading] = useState(false);

	const load = useCallback(
		async (pageUrl = url) => {
			setLoading(true);
			const fullUrl = new URL(pageUrl, window.location.origin);
			try {
				const response = await jsonLdFetch(pageUrl);

				setItems(response["hydra:member"]);

				if (response["hydra:view"]) {
					setNext(response["hydra:view"]["hydra:next"] || null);
					setPrev(response["hydra:view"]["hydra:previous"] || null);
					setCurrentPage(
						new URL(fullUrl).searchParams.get("page") || 1
					);
					const lastPageUrl = response["hydra:view"]["hydra:last"];
					setNumberPage(lastPageUrl.match(/\d+/)[0]);
					setLoading(false);
				}
			} catch (error) {
				console.error(error);
				setLoading(false);
			}
		},
		[url]
	);

	return {
		items,
		load,
		next,
		prev,
		currentPage,
		numberPage,
		loading,
	};
}
