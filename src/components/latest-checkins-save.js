const Save = (props) => {
	const {
		attributes: {
			userName,
		},
		className
	} = props;

	return (
		<div className={className}>
			{userName && (
				<p>{userName}</p>
			)}
		</div>
	);
};

export default Save;
