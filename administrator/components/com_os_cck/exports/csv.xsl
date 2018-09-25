<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="text" encoding="windows-1251"/>
    <xsl:template match="/">
        <xsl:for-each select="houses_data/houses_list/house">
            <xsl:value-of select="itemid"/><xsl:text>|</xsl:text>
            <xsl:value-of select="description"/><xsl:text>|</xsl:text>
            <xsl:value-of select="link"/><xsl:text>|</xsl:text>
            <xsl:value-of select="listing_type"/><xsl:text>|</xsl:text>
            <xsl:value-of select="price"/><xsl:text>|</xsl:text>
            <xsl:value-of select="price_type"/><xsl:text>|</xsl:text>
            <xsl:value-of select="htitle"/><xsl:text>|</xsl:text>
            <xsl:value-of select="hlocation"/><xsl:text>|</xsl:text>
            <xsl:value-of select="hlatitude"/><xsl:text>|</xsl:text>
            <xsl:value-of select="hlongitude"/><xsl:text>|</xsl:text>
            <xsl:value-of select="bathrooms"/><xsl:text>|</xsl:text>
            <xsl:value-of select="bedrooms"/><xsl:text>|</xsl:text>
            <xsl:value-of select="broker"/><xsl:text>|</xsl:text>
            <xsl:value-of select="image_link"/><xsl:text>|</xsl:text>
            <xsl:value-of select="listing_status"/><xsl:text>|</xsl:text>
            <xsl:value-of select="property_type"/><xsl:text>|</xsl:text>
            <xsl:value-of select="provider_class"/><xsl:text>|</xsl:text>
            <xsl:value-of select="year"/><xsl:text>|</xsl:text>
            <xsl:value-of select="agent"/><xsl:text>|</xsl:text>
            <xsl:value-of select="area"/><xsl:text>|</xsl:text>
            <xsl:value-of select="expiration_date"/><xsl:text>|</xsl:text>
            <xsl:value-of select="feature"/><xsl:text>|</xsl:text>
            <xsl:value-of select="hoa_dues"/><xsl:text>|</xsl:text>
            <xsl:value-of select="lot_size"/><xsl:text>|</xsl:text>
            <xsl:value-of select="model"/><xsl:text>|</xsl:text>
            <xsl:value-of select="property_taxes"/><xsl:text>|</xsl:text>
            <xsl:value-of select="school"/><xsl:text>|</xsl:text>
            <xsl:value-of select="school_district"/><xsl:text>|</xsl:text>
            <xsl:value-of select="style"/><xsl:text>|</xsl:text>
            <xsl:value-of select="zoning"/><xsl:text>|</xsl:text>
            <xsl:value-of select="date"/><xsl:text>|</xsl:text>
            <xsl:value-of select="hits"/><xsl:text>&#xA;</xsl:text>
        </xsl:for-each>
    </xsl:template>
</xsl:stylesheet>

