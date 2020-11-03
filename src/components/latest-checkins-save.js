const Save = (props) => {
	const {
		attributes: {
			userName,
			title,
			limit,
		},
		className
	} = props;

	return (
		<div className={className}>
			<p>{title} {userName} {limit}</p>
		</div>
	);
};

export default Save;
