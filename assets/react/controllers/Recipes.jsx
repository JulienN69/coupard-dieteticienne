import React, { useEffect } from "react";
import useLoadData from "../hooks/useFetch";
import { ClipLoader } from "react-spinners";

export default function Recipes({ url, disablePagination }) {
	const { items, load, next, prev, currentPage, numberPage, loading } =
		useLoadData(url);

	useEffect(() => {
		load();
	}, [load]);

	// Fonction pour générer les numéros de page
	const renderPageNumbers = () => {
		const pages = [];
		for (let i = 1; i <= numberPage; i++) {
			pages.push(
				<button
					key={i}
					onClick={() => load(`${url}?page=${i}`)}
					className={i === Number(currentPage) ? "active" : ""}
				>
					{i}
				</button>
			);
		}
		return pages;
	};

	if (loading) {
		return (
			<div className="clipLoader">
				<ClipLoader color="#609A7D" loading={true} size={50} />
			</div>
		);
	}

	return (
		<>
			<div className="recipes">
				{items.map((recipe, index) => (
					<a
						key={index}
						className="recipe-card"
						href={`/recipe/show/${recipe.id}`}
					>
						<img
							className="recipe-card__image"
							src={`/images/recipe/${recipe.image}`}
							alt={recipe.title}
						/>
						<h3 className="recipe-card__title">
							{recipe.title}
							{recipe.id}
						</h3>
						{recipe.diets.map((diet, dietIndex) => (
							<span
								key={dietIndex}
								className="recipe-card__diets"
								id={diet.id}
							>
								{diet.name}
							</span>
						))}
						<span className="recipe-card__line"></span>
						<p className="recipe-card__description">
							{recipe.description}
						</p>
					</a>
				))}
			</div>

			{!disablePagination && (
				<div className="pagination">
					{prev && (
						<button onClick={() => load(prev)} className="prev">
							&lt;
						</button>
					)}

					{renderPageNumbers()}

					{next && (
						<button onClick={() => load(next)} className="next">
							&gt;
						</button>
					)}
				</div>
			)}
		</>
	);
}
